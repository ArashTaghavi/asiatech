<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Food;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{

    public function index(): JsonResource
    {
        return JsonResource::collection(Order::with('items')->get());
    }

    /**
     * @param Request $request
     * @return JsonResource
     */
    public function store(Request $request): JsonResource
    {
        $order = new Order();

        if ($this->checkStock($request->all()[0])) {


            $order_items = [];

            $total = $this->amount($request->all()[0]);

            DB::transaction(function () use ($order, $request, $total, $order_items) {

                $order->address_id = $request->all()[1]['address_id'];
                $order->total_payable = $total['total_payable'];
                $order->total_amount = $total['total_amount'];
                $order->authority = Str::random(25);

                $order->save();

                foreach ($request->all()[0] as $order_item) {
                    $order_items[] = [
                        'order_id' => $order->id,
                        'quantity' => $order_item['quantity'],
                        'food_id' => $order_item['food_id']
                    ];
                }

                OrderItem::insert($order_items);

                $this->foodStockHandler($request->all()[0]);
            });
        } else {
            abort('400', 'foods not exist');
        }

        return JsonResource::collection($order->items);

    }

    /**
     * @return JsonResource
     */
    public function user_orders(): JsonResource
    {
        $orders = Order::whereHas('address', function ($q) {
            $q->where('user_id', auth()->id());
        })->with('items')->get();

        return JsonResource::collection($orders);
    }

    /**
     * @param array $order_items
     * @return bool
     */
    private function checkStock(array $order_items): bool
    {
        $foods_id = [];

        $result = true;

        foreach ($order_items as $order_item) {
            $foods_id[] = $order_item['food_id'];
        }

        $foods = Food::whereIn('id', $foods_id)->get();

        foreach ($order_items as $order_item) {
            foreach ($foods as $food) {
                if ($food->stock == 0) {
                    $result = false;
                    break;
                }
                if ($food->stock < $order_item['quantity']) {
                    $result = false;
                    break;
                }

            }
            if (!$result)
                break;
        }

        return $result;
    }

    /**
     * @param array $order_items
     * @return array
     */
    private function amount(array $order_items): array
    {
        $foods_id = [];

        foreach ($order_items as $order_item) {
            $foods_id[] = $order_item['food_id'];
        }

        $foods = Food::whereIn('id', $foods_id)->get();

        $total_payable = 0;
        $total_amount = 0;

        foreach ($foods as $food) {
            $total_amount += $food->price;
            $total_payable += $food->price - ($food->price * $food->discount / 100);
        }

        return compact('total_payable', 'total_amount');

    }

    /**
     * @param array $order_items
     */
    private function foodStockHandler(array $order_items): void
    {
        $foods_id = [];

        foreach ($order_items as $order_item) {
            $foods_id[] = $order_item['food_id'];
        }

        $foods = Food::whereIn('id', $foods_id)->get();

        foreach ($order_items as $order_item) {
            foreach ($foods as $food) {
                if ($food->id === $order_item['food_id']) {
                    $food->stock = $food->stock - $order_item['quantity'];
                    $food->save();
                }
            }
        }

    }
}
