<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\ProductGraphics;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\ProductVariantCombination;
use App\Models\VariantValue;

class ProductMetaExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    use Exportable;

    protected $filters;
    protected $srNo = 1;
    protected $columnCount = 32; // Total columns in export

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Product::query();
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Sr No.',
            'Id',
            'Title',
            'Description',
            'Availability', 
            'Condition', 
            'Price', 
            'Link', 
            'Image Link', 
            'Brand', 
            'Google Product Category', 
            'FB Product Category', 
            'Quantity To Sell On Facebook',
            'Sale Price',
            'Sale Price Effective_Date', 
            'Item Group Id',
            'Gender', 
            'Color', 
            'Size',
            'Age_Group', 
            'Material', 
            'Pattern', 
            'Shipping', 
            'Shipping Weight', 
            'Offer Disclaimer', 
            'Offer Disclaimer Url', 
            'Video Url', 
            'Video Tag', 
            'GTIN',
            'Product Pages', 
            'Product Tags', 
            'Style'
        ];
    }

    public function map($product): array
    {
        $productImg = ProductGraphics::select('graphic')->where('status', 1)->where('is_front', 1)->where('product_id', $product->id)->first();
        if(!empty($productImg->graphic)) {  
            $img_link = 'https://vasvi.in/uploads/products/' . $productImg->graphic; 
        } else { 
            $img_link = 'https://vasvi.in/uploads/settings/FEB2026/1770814794-settings.png'; 
        } 
		
		/*
		$productVarient = ProductVariantCombination::where('product_id', $product->id)->first();
		$varient = @$productVarient->sku;
		$varientArr = explode('_',$varient);
		$color = 'Black Blue Red Green';
		$size = 'XS S M L XL XXL';
		if(isset($varientArr[0]) && !empty($varientArr[0])){
			$color =$varientArr[0];
		}
		if(isset($varientArr[1]) && !empty($varientArr[1])){
			$size =$varientArr[1];
		}
		*/
		
		$buying_price = str_replace(',', '',number_format($product->buying_price, 2, '.', ',') . ' INR');
		$selling_price = str_replace(',', '',number_format($product->selling_price, 2, '.', ',') . ' INR');

		$productVarient = ProductVariantCombination::where('product_id', $product->id)->get(); // get combination_id
		if(!empty($productVarient)){
			foreach($productVarient as $varient){
				$combination_id = @$varient->combination_id;
				$product_varient_sku = @$varient->sku;
				
				$varientValueArr = explode(',',trim($combination_id, '[]'));
				if(isset($varientValueArr[0]) && !empty($varientValueArr[0]) && isset($varientValueArr[1]) && !empty($varientValueArr[1])){
					$varientColorArr = VariantValue::where('id', @$varientValueArr[0])->first();
					$varientSizeArr = VariantValue::where('id', @$varientValueArr[1])->first();
					
					$color = 'Black Blue Red Green';
					$size = 'XS S M L XL XXL';
					if(!empty($varientColorArr)){
						$color = $varientColorArr->name;
					}
					if(!empty($varientSizeArr)){
						$size =$varientSizeArr->name;
					}
					return [
						$this->srNo++, // Sr 
						$product_varient_sku ?? random_int(100000, 999999),  // id 
						$product->name ?? '',  // title 
						$product->short_description ?? $product->name, // description 
						$product->in_stock ? 'in stock' : 'out stock',  // availability 
						$product->is_new ? 'new' : 'old',  // condition 
						$buying_price,  // price 
						'https://vasvi.in/' .$product->sku .'/'. $product->slug,  // link 
						$img_link,  //image_link
						'VASVI',
						
						$product->mainCategory->name ?? '', // google_product_category (Apparel & Accessories > Clothing)
						$product->mainCategory->name ?? '', // fb_product_category (Clothing & Accessories > Clothing)
						$product->qty ?? '1', // quantity_to_sell_on_facebook
						$selling_price,  // price
						'', // sale_price_effective_date (2020-04-30T09:30-08:00/2020-05-30T23:59-08:00)
						$product->sku ?? '', // item_group_id
						'unisex', // gender (Supported values: female; male; unisex)
						$color, // color (royal blue)
						$size, // size (For example: small; XL; 12. Character limit: 200.)
						'adult; all ages; infant; kids; newborn; teen; toddler', // age_group (Supported values: adult; all ages; infant; kids; newborn; teen; toddler)
						'cotton', // material (such as cotton; denim or leather. Character limit: 200.)
						'graphic print', // pattern (The pattern or graphic print on the item. Character limit: 100.)
						'', // shipping (US:CA:Ground:9.99 USD;US:NY:Air:15.99 USD)
						$product->weight ? $product->weight . ' ' . ($product->weight_type ?? 'grm') : '', // shipping_weight (10 kg)
						'', // offer_disclaimer (Valid while supplies last. Terms and conditions apply.)
						'', // offer_disclaimer_url (https://example.com/terms-and-conditions)
						'', // video[0].url (http://www.facebook.com/a0.mp4)
						'', // video[0].tag[0] (Gym)
						'', // gtin
						'', // product_tags[0] (some_string)
						'', // product_tags[1] (other)
						'', // style[0] (Bodycon)
			 
					];
				}else {
					return [
						$this->srNo++, // Sr 
						$product->id ?? '',  // id 
						$product->name ?? '',  // title 
						$product->short_description ?? $product->name, // description 
						$product->in_stock ? 'in stock' : 'out stock',  // availability 
						$product->is_new ? 'new' : 'old',  // condition 
						$buying_price,  // price 
						'https://vasvi.in/' .$product->sku .'/'. $product->slug,  // link 
						$img_link,  //image_link
						'VASVI',
						
						$product->mainCategory->name ?? '', // google_product_category (Apparel & Accessories > Clothing)
						$product->mainCategory->name ?? '', // fb_product_category (Clothing & Accessories > Clothing)
						$product->qty ?? '1', // quantity_to_sell_on_facebook
						$selling_price,  // price
						'', // sale_price_effective_date (2020-04-30T09:30-08:00/2020-05-30T23:59-08:00)
						$product->sku ?? '', // item_group_id
						'unisex', // gender (Supported values: female; male; unisex)
						'', // color (royal blue)
						'', // size (For example: small; XL; 12. Character limit: 200.)
						'adult; all ages; infant; kids; newborn; teen; toddler', // age_group (Supported values: adult; all ages; infant; kids; newborn; teen; toddler)
						'cotton', // material (such as cotton; denim or leather. Character limit: 200.)
						'graphic print', // pattern (The pattern or graphic print on the item. Character limit: 100.)
						'', // shipping (US:CA:Ground:9.99 USD;US:NY:Air:15.99 USD)
						$product->weight ? $product->weight . ' ' . ($product->weight_type ?? 'grm') : '', // shipping_weight (10 kg)
						'', // offer_disclaimer (Valid while supplies last. Terms and conditions apply.)
						'', // offer_disclaimer_url (https://example.com/terms-and-conditions)
						'', // video[0].url (http://www.facebook.com/a0.mp4)
						'', // video[0].tag[0] (Gym)
						'', // gtin
						'', // product_tags[0] (some_string)
						'', // product_tags[1] (other)
						'', // style[0] (Bodycon)
			 
					];
				}
			}
		} else {
			return [
				$this->srNo++, // Sr 
				$product->id ?? '',  // id 
				$product->name ?? '',  // title 
				$product->short_description ?? $product->name, // description 
				$product->in_stock ? 'in stock' : 'out stock',  // availability 
				$product->is_new ? 'new' : 'old',  // condition 
				$buying_price,  // price 
				'https://vasvi.in/' .$product->sku .'/'. $product->slug,  // link 
				$img_link,  //image_link
				'VASVI',
				
				$product->mainCategory->name ?? '', // google_product_category (Apparel & Accessories > Clothing)
				$product->mainCategory->name ?? '', // fb_product_category (Clothing & Accessories > Clothing)
				$product->qty ?? '1', // quantity_to_sell_on_facebook
				$selling_price,  // price
				'', // sale_price_effective_date (2020-04-30T09:30-08:00/2020-05-30T23:59-08:00)
				$product->sku ?? '', // item_group_id
				'unisex', // gender (Supported values: female; male; unisex)
				'', // color (royal blue)
				'', // size (For example: small; XL; 12. Character limit: 200.)
				'adult; all ages; infant; kids; newborn; teen; toddler', // age_group (Supported values: adult; all ages; infant; kids; newborn; teen; toddler)
				'cotton', // material (such as cotton; denim or leather. Character limit: 200.)
				'graphic print', // pattern (The pattern or graphic print on the item. Character limit: 100.)
				'', // shipping (US:CA:Ground:9.99 USD;US:NY:Air:15.99 USD)
				$product->weight ? $product->weight . ' ' . ($product->weight_type ?? 'grm') : '', // shipping_weight (10 kg)
				'', // offer_disclaimer (Valid while supplies last. Terms and conditions apply.)
				'', // offer_disclaimer_url (https://example.com/terms-and-conditions)
				'', // video[0].url (http://www.facebook.com/a0.mp4)
				'', // video[0].tag[0] (Gym)
				'', // gtin
				'', // product_tags[0] (some_string)
				'', // product_tags[1] (other)
				'', // style[0] (Bodycon)
	 
			];
		}
	}

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Header styling
                $columnCount = $this->columnCount;
                $headerRange = 'A1:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnCount) . '1';

                $event->sheet->getStyle($headerRange)->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFFF00'], // Yellow
                    ],
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => '000000'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Auto-size all columns
                for ($i = 1; $i <= $columnCount; $i++) {
                    $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
