<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Wishlist;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;


class ProfileController extends Controller
{


    public function index()
    {
        $customer = auth('customer')->user();

        // Total orders
        $totalOrders = Order::where('customer_id', $customer->id)->count();

        // Total amount spent (only paid orders)
        $totalSpent = Order::where('customer_id', $customer->id)
                            ->where('payment_status', 'paid')
                            ->sum('total');

        // Total wishlist items
        $wishlistCount = Wishlist::where('customer_id', $customer->id)->count();

        return view('user.profile', compact(
            'totalOrders',
            'totalSpent',
            'wishlistCount'
        ));
    }





    public function orders()
    {
        $customer = auth('customer')->user();

        $orders = Order::with([
                'items.product'
            ])
            ->where('customer_id', $customer->id)
            ->orderByDesc('created_at')
            ->get();

        return view('user.orders', compact('orders'));
    }



    public function orderDetails(Order $order)
    {
        // 🔒 Security: ensure order belongs to logged-in customer
        if ($order->customer_id !== auth('customer')->id()) {
            abort(403);
        }

        return view('user.order-details', [
            'order' => $order->load(['items.product']), // eager load relations
        ]);
    }




        /* Addess Management */

    public function addresses()
    {

        $data['addresses'] = auth('customer')->user()->addresses()->orderByDesc('is_default')->get();

        return view('user.addresses',$data);

    }



    public function create_address()
    {

        return view('user.address-add');

    }



    public function store_address(Request $request)
    {
        $data = $request->validate([
            'fullname'       => 'required|string',
            'email'          => 'nullable|email',
            'phone'          => 'nullable|string',
            'address_line1'  => 'required|string',
            'city'           => 'required|string',
            'state'          => 'nullable|string',
            'country'        => 'required|string',
            'postal_code'    => 'nullable|string',
            'address_type'   => 'required|in:home,work',
            'is_default'     => 'nullable|boolean',
        ]);

        $data['customer_id'] = auth('customer')->id();

        // If first address OR marked default → unset others
        if (!CustomerAddress::where('customer_id', $data['customer_id'])->exists()
            || ($request->boolean('is_default'))
        ) {
            CustomerAddress::where('customer_id', $data['customer_id'])
                ->update(['is_default' => false]);

            $data['is_default'] = true;
        }

        CustomerAddress::create($data);

        return redirect()->route('addresses')
            ->with('success', 'Address added successfully');
    }






    public function edit_address(CustomerAddress $address)
    {
        $this->authorizeAddress($address);

        return view('user.address-edit', compact('address'));
    }

    public function update_address(Request $request, CustomerAddress $address)
    {
        $this->authorizeAddress($address);

        $data = $request->validate([
            'fullname'       => 'required|string',
            'email'          => 'nullable|email',
            'phone'          => 'nullable|string',
            'address_line1'  => 'required|string',
            'city'           => 'required|string',
            'state'          => 'nullable|string',
            'country'        => 'required|string',
            'postal_code'    => 'nullable|string',
            'address_type'   => 'required|in:home,work',
        ]);

        $address->update($data);

        return redirect()->route('addresses')
            ->with('success', 'Address updated successfully');
        }

        public function destroy_address(CustomerAddress $address)
        {
            $this->authorizeAddress($address);

            $address->delete();

            return back()->with('success', 'Address deleted successfully');
        }

        public function setDefault_address(CustomerAddress $address)
        {
            $this->authorizeAddress($address);

            CustomerAddress::where('customer_id', auth('customer')->id())
                ->update(['is_default' => false]);

            $address->update(['is_default' => true]);

            return back()->with('success', 'Default address updated');
        }

        private function authorizeAddress(CustomerAddress $address)
        {
            abort_if(
                $address->customer_id !== auth('customer')->id(),
                403
            );
        }







        /* ADdress Management ewnd */


       

       



}
