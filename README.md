# DEPRECATED

## Only reference use purpose


[![No Maintenance Intended](http://unmaintained.tech/badge.svg)](http://unmaintained.tech/)

DEPRECATED - it was tested in very old magento I guess 2.1 or something, please dont use this package for production, use just as example of workaround

Why does magento API  disallow  bulk import of products? Because API should work with whole backend logic of interceptors, observers etc.
But if we certainly know, that we don't need all that stuff like interceptors, and need to make API bulk import, just like native csv import, but via API -  what we can do?
Anyway we need to create endpoint to get products data to import and we also need some model to process that data to database.
As you know native magento import working with import in bulk mode, you can see it here 

vendor/magento/module-catalog-import-export/Model/Import/Product.php

You can find such things as:

1. while ($bunch = $this->_dataSourceModel->getNextBunch()) {
2. $this->saveProductEntity( $entityRowsIn, $entityRowsUp
3. $this->_connection->insertOnDuplicate($entityTable, $entityRowsUp, ['updated_at', 'attribute_set_id']);
4. $this->_connection->insertMultiple($entityTable, $entityRowsIn);

So I decided to use that core possibilities without any duplication. This will avoid bugs in case if we are writing own bulk, and uses core features, what I like to do.
When I debug import, I saw, that import module has own adapters and source models.
We can add new adapters for supporting not only csv but xml, xsl etc files. But with API we don't have any file - so we need to create adapter without any file params.
So in fact 
1. app/code/SwayOleg/ApiBulk/Model/Import/Source/Api.php  - Our source iterator model
2. app/code/SwayOleg/ApiBulk/Model/Import/Adapter.php  - Adapter creator, hook to ignore file parameters and create source adapter
3. app/code/SwayOleg/ApiBulk/Model/Product.php - Endpoint logic here

Installation
1. Copy SwayOleg folder to your /app/code
2. enable module by magento module:enable SwayOleg_ApiBulk
3. run upgrade with magento setup:upgrade
4. flush cache with magento cache:flush

Response example 

```$json
[
    {
        "messages": [
            "Import successfully done"
        ],
        "created_items": 1,
        "updated_items": 1,
        "processed_entities": 4,
        "processed_rows": 2
    }
]
```

Request Example (Use raw body) 

```
POST /rest/V1/product/bulk/ HTTP/1.1
Host: magento220.dev
Authorization: Bearer mrun1k5i3rwov2qtn5cg0qmp2py03ej9
Accept: application/json
Content-Type: application/json
Cache-Control: no-cache
Postman-Token: 7439b450-dbcc-faa5-eeef-fe0d1b762f7b

{
	"data": {
		"options":{
			"entity":"catalog_product",
			"behavior": "append",
			"validation_strategy":"validation-skip-errors",
			"allowed_error_count": 10
		},
		"products": [
          {
            "sku": "24-MB01",
            "store_view_code": "",
            "attribute_set_code": "Bag",
            "product_type": "simple",
            "categories": "Default Category/Gear,Default Category/Gear/Bags",
            "product_websites": "base",
            "name": "Joust Duffle Bag",
            "description": "<p>The sporty Joust Duffle Bag can't be beat - not in the gym, not on the luggage carousel, not anywhere. Big enough to haul a basketball or soccer ball and some sneakers with plenty of room to spare, it's ideal for athletes with places to go.<p>\n<ul>\n<li>Dual top handles.</li>\n<li>Adjustable shoulder strap.</li>\n<li>Full-length zipper.</li>\n<li>L 29\" x W 13\" x H 11\".</li>\n</ul>",
            "short_description": "",
            "weight": "",
            "product_online": 1,
            "tax_class_name": "",
            "visibility": "Catalog, Search",
            "price": 34,
            "special_price": "",
            "special_price_from_date": "",
            "special_price_to_date": "",
            "url_key": "joust-duffle-bag",
            "meta_title": "",
            "meta_keywords": "",
            "meta_description": "",
            "base_image": "/m/b/mb01-blue-0.jpg",
            "base_image_label": "",
            "small_image": "/m/b/mb01-blue-0.jpg",
            "small_image_label": "",
            "thumbnail_image": "/m/b/mb01-blue-0.jpg",
            "thumbnail_image_label": "",
            "swatch_image": "",
            "swatch_image_label": "",
            "created_at": "10/14/17, 7:18 PM",
            "updated_at": "10/14/17, 7:18 PM",
            "new_from_date": "",
            "new_to_date": "",
            "display_product_options_in": "",
            "map_price": "",
            "msrp_price": "",
            "map_enabled": "",
            "gift_message_available": "",
            "custom_design": "",
            "custom_design_from": "",
            "custom_design_to": "",
            "custom_layout_update": "",
            "page_layout": "",
            "product_options_container": "",
            "msrp_display_actual_price_type": "",
            "country_of_manufacture": "",
            "additional_attributes": "",
            "qty": 100,
            "out_of_stock_qty": 0,
            "use_config_min_qty": 1,
            "is_qty_decimal": 0,
            "allow_backorders": 0,
            "use_config_backorders": 1,
            "min_cart_qty": 1,
            "use_config_min_sale_qty": 1,
            "max_cart_qty": 0,
            "use_config_max_sale_qty": 1,
            "is_in_stock": 1,
            "notify_on_stock_below": "",
            "use_config_notify_stock_qty": 1,
            "manage_stock": 0,
            "use_config_manage_stock": 1,
            "use_config_qty_increments": 1,
            "qty_increments": 0,
            "use_config_enable_qty_inc": 1,
            "enable_qty_increments": 0,
            "is_decimal_divided": 0,
            "website_id": 0,
            "related_skus": "",
            "related_position": "",
            "crosssell_skus": "",
            "crosssell_position": "",
            "upsell_skus": "",
            "upsell_position": "",
            "additional_images": "/m/b/mb01-blue-0.jpg",
            "additional_image_labels": "Image",
            "hide_from_product_page": "",
            "custom_options": "",
            "bundle_price_type": "",
            "bundle_sku_type": "",
            "bundle_price_view": "",
            "bundle_weight_type": "",
            "bundle_values": "",
            "bundle_shipment_type": "",
            "configurable_variations": "",
            "configurable_variation_labels": "",
            "associated_skus": ""
          },
          {
            "sku": "24-MB04",
            "store_view_code": "",
            "attribute_set_code": "Bag",
            "product_type": "simple",
            "categories": "Default Category/Gear,Default Category/Collections,Default Category/Gear/Bags",
            "product_websites": "base",
            "name": "Strive Shoulder Pack",
            "description": "<p>Convenience is next to nothing when your day is crammed with action. So whether you're heading to class, gym, or the unbeaten path, make sure you've got your Strive Shoulder Pack stuffed with all your essentials, and extras as well.</p>\n<ul>\n<li>Zippered main compartment.</li>\n<li>Front zippered pocket.</li>\n<li>Side mesh pocket.</li>\n<li>Cell phone pocket on strap.</li>\n<li>Adjustable shoulder strap and top carry handle.</li>\n</ul>",
            "short_description": "",
            "weight": "",
            "product_online": 1,
            "tax_class_name": "Taxable Goods",
            "visibility": "Catalog, Search",
            "price": 32,
            "special_price": 32,
            "special_price_from_date": "10/14/17",
            "special_price_to_date": "",
            "url_key": "strive-shoulder-pack",
            "meta_title": "",
            "meta_keywords": "",
            "meta_description": "",
            "base_image": "/m/b/mb04-black-0.jpg",
            "base_image_label": "",
            "small_image": "/m/b/mb04-black-0.jpg",
            "small_image_label": "",
            "thumbnail_image": "/m/b/mb04-black-0.jpg",
            "thumbnail_image_label": "",
            "swatch_image": "",
            "swatch_image_label": "",
            "created_at": "10/14/17, 7:18 PM",
            "updated_at": "10/14/17, 7:18 PM",
            "new_from_date": "",
            "new_to_date": "",
            "display_product_options_in": "Block after Info Column",
            "map_price": "",
            "msrp_price": "",
            "map_enabled": "",
            "gift_message_available": "",
            "custom_design": "",
            "custom_design_from": "",
            "custom_design_to": "",
            "custom_layout_update": "",
            "page_layout": "",
            "product_options_container": "",
            "msrp_display_actual_price_type": "",
            "country_of_manufacture": "",
            "additional_attributes": "activity=Gym|Hiking|Trail|Urban,erin_recommends=Yes,features_bags=Audio Pocket|Waterproof|Lightweight|Laptop Sleeve,material=Canvas|Cotton|Mesh|Polyester,sale=Yes,strap_bags=Adjustable|Cross Body|Padded|Shoulder|Single,style_bags=Messenger|Exercise|Tote",
            "qty": 98,
            "out_of_stock_qty": 0,
            "use_config_min_qty": 1,
            "is_qty_decimal": 0,
            "allow_backorders": 0,
            "use_config_backorders": 1,
            "min_cart_qty": 1,
            "use_config_min_sale_qty": 1,
            "max_cart_qty": 0,
            "use_config_max_sale_qty": 1,
            "is_in_stock": 1,
            "notify_on_stock_below": "",
            "use_config_notify_stock_qty": 1,
            "manage_stock": 0,
            "use_config_manage_stock": 1,
            "use_config_qty_increments": 1,
            "qty_increments": 0,
            "use_config_enable_qty_inc": 1,
            "enable_qty_increments": 0,
            "is_decimal_divided": 0,
            "website_id": 0,
            "related_skus": "",
            "related_position": "",
            "crosssell_skus": "",
            "crosssell_position": "",
            "upsell_skus": "",
            "upsell_position": "",
            "additional_images": "/m/b/mb04-black-0.jpg,/m/b/mb04-black-0_alt1.jpg",
            "additional_image_labels": "Image,Image",
            "hide_from_product_page": "",
            "custom_options": "",
            "bundle_price_type": "",
            "bundle_sku_type": "",
            "bundle_price_view": "",
            "bundle_weight_type": "",
            "bundle_values": "",
            "bundle_shipment_type": "",
            "configurable_variations": "",
            "configurable_variation_labels": "",
            "associated_skus": ""
          }
	    ]
    }
}

```
