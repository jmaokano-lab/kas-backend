<?php

namespace App\Http\Resources\V2;

use App\Models\Category;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductMiniCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                $wholesale_product =
                    ($data->wholesale_product == 1) ? true : false;
                $category = Category::where('id', $data->category_id)->get();

                $precision = 2;
                $calculable_price = home_discounted_base_price($data, false);
                $calculable_price = number_format($calculable_price, $precision, '.', '');
                $calculable_price = floatval($calculable_price);


                return [
                    'id' => $data->id,

                    'slug' => $data->slug,
                    'name' => $data->getTranslation('name'),
                    'slug' => $data->slug,
                    'category' => new CategoryCollection($category),
                    'thumbnail_image' => uploaded_asset($data->thumbnail_img),
                    'has_discount' => home_base_price($data, false) != home_discounted_base_price($data, false),
                    'discount' => "-" . discount_in_percentage($data) . "%",
                    'stroked_price' => home_base_price($data),
                    'main_price' => home_discounted_base_price($data),
                    'rating' => (float) $data->rating,
                    'sales' => (int) $data->num_of_sale,
                    'is_wholesale' => $wholesale_product,
                    'calculable_price' => $calculable_price,
                    'all_stock' => $data->stocks->select(
                        'id',
                        'variant',
                        'price',
                        'qty',
                        'image'
                    ),
                    'links' => [
                        'details' => route('products.show', ['slug' => $data->slug, 'user_id' => $data->user_id]),
                    ]
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
