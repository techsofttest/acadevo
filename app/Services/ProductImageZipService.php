<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class ProductImageZipService
{
    /**
     * Supported image extensions
     */
    protected array $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'avif'];

    /**
     * Extract product images from a zip file and link/save them to storage (storage/app/public/products).
     *
     * Behavior:
     * 1. Extracts all valid images into storage/app/public/products/
     * 2. Automatically matches images named after Product SKU or Product Slug or Product ID:
     *    - e.g. "PRD-001.jpg", "PRD-001-1.jpg", "PRD-001-2.jpg", "product-slug.png", "my-slug-main.jpg"
     *    - Main image is assigned to $product->image
     *    - Secondary/additional images are assigned to $product->additional_images
     * 3. Even if a filename does not match an existing product, it is safely extracted into `products/`
     *    so it is immediately available for product attachments or manual selection.
     *
     * @param string $zipFilePath Absolute path to the zip file
     * @return array ['total_extracted' => int, 'products_matched' => int, 'errors' => array]
     */
    public function import(string $zipFilePath): array
    {
        if (!class_exists('ZipArchive')) {
            throw new \Exception('PHP ZipArchive extension is not enabled on this server.');
        }

        $zip = new ZipArchive();
        $status = $zip->open($zipFilePath);

        if ($status !== true) {
            throw new \Exception('Unable to open the uploaded ZIP file. Status code: ' . $status);
        }

        $totalExtracted = 0;
        $matchedProductIds = [];
        $errors = [];
        $extractedImages = []; // ['basename' => 'products/filename.ext', 'filename_without_ext' => string]

        // Ensure target directory exists in public disk
        Storage::disk('public')->makeDirectory('products');
        $targetDir = Storage::disk('public')->path('products');

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);

            // Skip __MACOSX and hidden directory files
            if (str_contains($filename, '__MACOSX') || str_starts_with(basename($filename), '.')) {
                continue;
            }

            // Skip directories
            if (str_ends_with($filename, '/')) {
                continue;
            }

            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if (!in_array($extension, $this->allowedExtensions)) {
                continue;
            }

            $basename = basename($filename);
            // Sanitize filename
            $nameOnly = pathinfo($basename, PATHINFO_FILENAME);
            $safeName = Str::slug($nameOnly) . '.' . $extension;

            // Stream content from zip to storage
            $stream = $zip->getStream($filename);
            if (!$stream) {
                $errors[] = "Failed to read file: {$filename}";
                continue;
            }

            $destinationRelative = 'products/' . $safeName;
            $destinationAbsolute = $targetDir . DIRECTORY_SEPARATOR . $safeName;

            $destStream = fopen($destinationAbsolute, 'w+b');
            if ($destStream === false) {
                fclose($stream);
                $errors[] = "Failed to write to {$destinationRelative}";
                continue;
            }

            stream_copy_to_stream($stream, $destStream);
            fclose($stream);
            fclose($destStream);

            $totalExtracted++;
            $extractedImages[] = [
                'relativePath' => $destinationRelative,
                'nameOnly'     => strtolower($nameOnly),
                'cleanName'    => strtolower(Str::slug($nameOnly)),
            ];
        }

        $zip->close();

        if ($totalExtracted === 0) {
            return [
                'total_extracted'  => 0,
                'products_matched' => 0,
                'errors'           => array_merge(['No valid image files (jpg, jpeg, png, webp, gif, svg) found in the ZIP.'], $errors),
            ];
        }

        // Match extracted images with products
        // We match by:
        // 1. SKU (exact or slugified)
        // 2. Slug
        // 3. Name (slugified)
        $allProducts = Product::all();

        foreach ($allProducts as $product) {
            $skuNorm = !empty($product->sku) ? strtolower(Str::slug($product->sku)) : null;
            $skuRaw  = !empty($product->sku) ? strtolower(trim($product->sku)) : null;
            $slugNorm = strtolower(trim($product->slug));
            $nameNorm = strtolower(Str::slug($product->name));

            $mainImage = null;
            $additionalImages = is_array($product->additional_images) ? $product->additional_images : [];

            foreach ($extractedImages as $img) {
                $imgName = $img['cleanName'];
                $rawImgName = $img['nameOnly'];

                // Check match conditions
                $isMatch = false;
                $isAdditional = false;

                // Match SKU
                if ($skuNorm && ($imgName === $skuNorm || $rawImgName === $skuRaw)) {
                    $isMatch = true;
                } elseif ($skuNorm && (str_starts_with($imgName, $skuNorm . '-') || str_starts_with($imgName, $skuNorm . '_'))) {
                    $isMatch = true;
                    $isAdditional = true;
                }
                // Match Slug
                elseif ($imgName === $slugNorm) {
                    $isMatch = true;
                } elseif (str_starts_with($imgName, $slugNorm . '-') || str_starts_with($imgName, $slugNorm . '_')) {
                    $isMatch = true;
                    $isAdditional = true;
                }
                // Match Name
                elseif ($imgName === $nameNorm) {
                    $isMatch = true;
                } elseif (str_starts_with($imgName, $nameNorm . '-') || str_starts_with($imgName, $nameNorm . '_')) {
                    $isMatch = true;
                    $isAdditional = true;
                }

                if ($isMatch) {
                    if (!$isAdditional && !$mainImage) {
                        $mainImage = $img['relativePath'];
                    } else {
                        if (!in_array($img['relativePath'], $additionalImages)) {
                            $additionalImages[] = $img['relativePath'];
                        }
                    }
                }
            }

            $updates = [];
            if ($mainImage) {
                $updates['image'] = $mainImage;
            } elseif (empty($product->image) && !empty($additionalImages)) {
                // If product has no main image yet, take the first additional image
                $updates['image'] = array_shift($additionalImages);
            }

            if (!empty($additionalImages)) {
                // Ensure unique values
                $updates['additional_images'] = array_values(array_unique($additionalImages));
            }

            if (!empty($updates)) {
                $product->update($updates);
                $matchedProductIds[$product->id] = true;
            }
        }

        return [
            'total_extracted'  => $totalExtracted,
            'products_matched' => count($matchedProductIds),
            'errors'           => $errors,
        ];
    }
}
