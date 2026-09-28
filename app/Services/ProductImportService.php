<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProductImportService
{
    /**
     * Import products and variants from an Excel / CSV file.
     *
     * Expected Columns:
     * Product Name | Category | Product SKU | Variant SKU | Value | Unit | Strike Price | Selling Price | Stock | Is Default | Description
     *
     * @param string $filePath Absolute or temporary path to file
     * @return array ['total' => int, 'products_created' => int, 'products_updated' => int, 'variants_created' => int, 'variants_updated' => int, 'errors' => array]
     */
    public function import(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray(null, true, true, true);

        if (empty($rows)) {
            return [
                'total'            => 0,
                'products_created' => 0,
                'products_updated' => 0,
                'variants_created' => 0,
                'variants_updated' => 0,
                'errors'           => ['The uploaded spreadsheet is empty.'],
            ];
        }

        // Detect header row and mapping
        $headerRow = null;
        $columnMap = [];
        $headerRowIndex = null;

        foreach ($rows as $rowIndex => $row) {
            $normalizedRow = array_map(function ($val) {
                return strtolower(trim((string) $val));
            }, $row);

            // Check if this row contains 'product name' or 'product' or 'name'
            if (
                in_array('product name', $normalizedRow) ||
                in_array('product_name', $normalizedRow) ||
                in_array('product', $normalizedRow)
            ) {
                $headerRow = $normalizedRow;
                $headerRowIndex = $rowIndex;

                foreach ($headerRow as $colLetter => $headerName) {
                    if (empty($headerName)) {
                        continue;
                    }

                    if (in_array($headerName, ['product name', 'product_name', 'product', 'title'])) {
                        $columnMap['product_name'] = $colLetter;
                    } elseif (in_array($headerName, ['category', 'category name', 'category_name'])) {
                        $columnMap['category'] = $colLetter;
                    } elseif (in_array($headerName, ['product sku', 'product_sku', 'main sku', 'main_sku', 'sku'])) {
                        $columnMap['product_sku'] = $colLetter;
                    } elseif (in_array($headerName, ['variant sku', 'variant_sku', 'var_sku'])) {
                        $columnMap['variant_sku'] = $colLetter;
                    } elseif (in_array($headerName, ['value', 'quantity', 'size', 'val'])) {
                        $columnMap['value'] = $colLetter;
                    } elseif (in_array($headerName, ['unit', 'uom', 'measurement'])) {
                        $columnMap['unit'] = $colLetter;
                    } elseif (in_array($headerName, ['strike price', 'strike_price', 'mrp', 'regular price', 'original price'])) {
                        $columnMap['strike_price'] = $colLetter;
                    } elseif (in_array($headerName, ['selling price', 'selling_price', 'price', 'offer price'])) {
                        $columnMap['selling_price'] = $colLetter;
                    } elseif (in_array($headerName, ['stock', 'qty', 'quantity available', 'stock_quantity'])) {
                        $columnMap['stock'] = $colLetter;
                    } elseif (in_array($headerName, ['is default', 'is_default', 'default', 'is default variant'])) {
                        $columnMap['is_default'] = $colLetter;
                    } elseif (in_array($headerName, ['description', 'desc', 'details'])) {
                        $columnMap['description'] = $colLetter;
                    }
                }
                break;
            }
        }

        if (!$headerRowIndex || !isset($columnMap['product_name'])) {
            // Default fallback column mapping based on standard template:
            // A: Product Name, B: Category, C: Product SKU, D: Variant SKU, E: Value, F: Unit, G: Strike Price, H: Selling Price, I: Stock, J: Is Default, K: Description
            $columnMap = [
                'product_name'  => 'A',
                'category'      => 'B',
                'product_sku'   => 'C',
                'variant_sku'   => 'D',
                'value'         => 'E',
                'unit'          => 'F',
                'strike_price'  => 'G',
                'selling_price' => 'H',
                'stock'         => 'I',
                'is_default'    => 'J',
                'description'   => 'K',
            ];
            $headerRowIndex = 1;
        }

        $productsCreated = 0;
        $productsUpdated = 0;
        $variantsCreated = 0;
        $variantsUpdated = 0;
        $totalProcessed = 0;
        $errors = [];

        // Cache categories by name/slug for speed
        $categoriesByName = Category::all()->keyBy(fn($c) => strtolower(trim($c->name)));

        // Keep track of products processed in this batch to avoid repeating product-level creation count
        $processedProductIds = [];

        foreach ($rows as $rowIndex => $row) {
            if ($rowIndex <= $headerRowIndex) {
                continue;
            }

            $productName = isset($columnMap['product_name']) && isset($row[$columnMap['product_name']]) ? trim((string)$row[$columnMap['product_name']]) : '';

            if (empty($productName)) {
                // Check if the entire row is empty
                $allEmpty = true;
                foreach ($row as $cellVal) {
                    if (!empty(trim((string)$cellVal))) {
                        $allEmpty = false;
                        break;
                    }
                }
                if ($allEmpty) {
                    continue;
                }
                $errors[] = "Row {$rowIndex}: Skipped because Product Name is empty.";
                continue;
            }

            $totalProcessed++;

            $categoryName = isset($columnMap['category']) && isset($row[$columnMap['category']]) ? trim((string)$row[$columnMap['category']]) : '';
            $productSku   = isset($columnMap['product_sku']) && isset($row[$columnMap['product_sku']]) ? trim((string)$row[$columnMap['product_sku']]) : null;
            $variantSku   = isset($columnMap['variant_sku']) && isset($row[$columnMap['variant_sku']]) ? trim((string)$row[$columnMap['variant_sku']]) : null;
            $value        = isset($columnMap['value']) && isset($row[$columnMap['value']]) ? trim((string)$row[$columnMap['value']]) : '1';
            $unit         = isset($columnMap['unit']) && isset($row[$columnMap['unit']]) ? trim((string)$row[$columnMap['unit']]) : 'pcs';
            $strikePrice  = isset($columnMap['strike_price']) && isset($row[$columnMap['strike_price']]) ? (float) trim((string)$row[$columnMap['strike_price']]) : 0.0;
            $sellingPrice = isset($columnMap['selling_price']) && isset($row[$columnMap['selling_price']]) ? (float) trim((string)$row[$columnMap['selling_price']]) : 0.0;
            $stock        = isset($columnMap['stock']) && isset($row[$columnMap['stock']]) ? (int) trim((string)$row[$columnMap['stock']]) : 0;
            $isDefaultRaw = isset($columnMap['is_default']) && isset($row[$columnMap['is_default']]) ? trim((string)$row[$columnMap['is_default']]) : '0';
            $description  = isset($columnMap['description']) && isset($row[$columnMap['description']]) ? trim((string)$row[$columnMap['description']]) : null;

            $isDefault = in_array(strtolower($isDefaultRaw), ['1', 'true', 'yes', 'y']);

            // Resolve Category
            $categoryId = null;
            if (!empty($categoryName)) {
                $lowerCatName = strtolower($categoryName);
                if (isset($categoriesByName[$lowerCatName])) {
                    $categoryId = $categoriesByName[$lowerCatName]->id;
                } else {
                    $newCategory = Category::create([
                        'name' => $categoryName,
                        'slug' => Str::slug($categoryName),
                    ]);
                    $categoriesByName[$lowerCatName] = $newCategory;
                    $categoryId = $newCategory->id;
                }
            }

            try {
                DB::beginTransaction();

                // Find Product by SKU (if provided) or by Name
                $product = null;
                if (!empty($productSku)) {
                    $product = Product::where('sku', $productSku)->first();
                }

                if (!$product) {
                    $product = Product::where('name', $productName)->first();
                }

                $isNewProduct = false;
                if ($product) {
                    $productUpdates = [];
                    if (!empty($productSku) && empty($product->sku)) {
                        $productUpdates['sku'] = $productSku;
                    }
                    if ($categoryId && $product->category_id !== $categoryId) {
                        $productUpdates['category_id'] = $categoryId;
                    }
                    if (!empty($description) && empty($product->description)) {
                        $productUpdates['description'] = $description;
                    }
                    if (!empty($productUpdates)) {
                        $product->update($productUpdates);
                    }

                    if (!in_array($product->id, $processedProductIds)) {
                        $productsUpdated++;
                        $processedProductIds[] = $product->id;
                    }
                } else {
                    // Generate unique slug
                    $baseSlug = Str::slug($productName);
                    $slug = $baseSlug;
                    $counter = 1;
                    while (Product::where('slug', $slug)->exists()) {
                        $slug = $baseSlug . '-' . $counter;
                        $counter++;
                    }

                    $product = Product::create([
                        'name'        => $productName,
                        'slug'        => $slug,
                        'sku'         => !empty($productSku) ? $productSku : null,
                        'category_id' => $categoryId,
                        'description' => $description,
                        'is_active'   => true,
                    ]);

                    $productsCreated++;
                    $processedProductIds[] = $product->id;
                    $isNewProduct = true;
                }

                // If this is the first variant of a new product or explicitly default, ensure default status
                if ($isNewProduct || $product->variants()->count() === 0) {
                    $isDefault = true;
                }

                if ($isDefault) {
                    // Unset default flag on other variants for this product
                    $product->variants()->update(['is_default' => false]);
                }

                // Resolve Variant by Variant SKU (if provided) or by (value, unit, product_id)
                $variant = null;
                if (!empty($variantSku)) {
                    $variant = ProductVariant::where('sku', $variantSku)->first();
                }

                if (!$variant) {
                    $variant = ProductVariant::where('product_id', $product->id)
                        ->where('value', $value)
                        ->where('unit', $unit)
                        ->first();
                }

                if ($variant) {
                    $variant->update([
                        'product_id'    => $product->id,
                        'sku'           => !empty($variantSku) ? $variantSku : $variant->sku,
                        'value'         => $value,
                        'unit'          => $unit,
                        'strike_price'  => $strikePrice,
                        'selling_price' => $sellingPrice,
                        'stock'         => $stock,
                        'is_default'    => $isDefault || $variant->is_default,
                    ]);
                    $variantsUpdated++;
                } else {
                    ProductVariant::create([
                        'product_id'    => $product->id,
                        'sku'           => !empty($variantSku) ? $variantSku : null,
                        'value'         => $value,
                        'unit'          => $unit,
                        'strike_price'  => $strikePrice,
                        'selling_price' => $sellingPrice,
                        'stock'         => $stock,
                        'is_default'    => $isDefault,
                    ]);
                    $variantsCreated++;
                }

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                $errors[] = "Row {$rowIndex} ({$productName}): " . $e->getMessage();
            }
        }

        return [
            'total'            => $totalProcessed,
            'products_created' => $productsCreated,
            'products_updated' => $productsUpdated,
            'variants_created' => $variantsCreated,
            'variants_updated' => $variantsUpdated,
            'errors'           => $errors,
        ];
    }
}
