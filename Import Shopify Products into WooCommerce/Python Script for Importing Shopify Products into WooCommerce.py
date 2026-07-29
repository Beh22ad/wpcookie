# Python Script for Importing Shopify Products into WooCommerce
# Code: Maya from WPCookie

import requests
import json
from woocommerce import API
from requests.adapters import HTTPAdapter
from requests.packages.urllib3.util.retry import Retry

wordpress_url ="https://0000000.com"
consumer__key ="000000000000000"
consumer__secret ="000000000000000"
json_file = '/000/000/products.json'

# WooCommerce API credentials
wcapi = API(
    url=wordpress_url,
    consumer_key=consumer__key,
    consumer_secret=consumer__secret,
    version="wc/v3",
    timeout=30  # Increase the timeout value
)

# Load Shopify JSON data
with open(json_file, 'r') as file:
    shopify_data = json.load(file)

# Function to clean image URL
def clean_image_url(url):
    return url.split('?')[0]

# Function to create attributes for variable products
def create_attributes(product_data):
    attributes = []
    for option in product_data.get("options", []):
        if option.get("values"):
            attributes.append({
                "name": option["name"],
                "visible": True,
                "variation": True,
                "options": option["values"]
            })
    return attributes

# Function to create variations
def create_variations(product_data, parent_weight, parent_image):
    variations = []
    for i, variant in enumerate(product_data["variants"]):
        variation = {
            "regular_price": variant["price"],
            "sku": variant.get("sku", str(variant["id"])),  # Use the exact SKU from the JSON file
            "manage_stock": False,  # Uncheck manage stock
            "in_stock": variant["available"],
            "weight": str(variant.get("grams", parent_weight) / 1000),
            "attributes": []
        }
        # Handle options for attributes
        for j, option_key in enumerate(["option1", "option2", "option3"], start=1):
            if variant.get(option_key):
                variation["attributes"].append({
                    "name": product_data["options"][j - 1]["name"],
                    "option": variant[option_key]
                })
        # Add image if available, fallback to parent image
        if variant.get("featured_image"):
            variation["image"] = {"src": clean_image_url(variant["featured_image"]["src"])}
        elif parent_image:
            variation["image"] = {"src": clean_image_url(parent_image)}

        # Set the first variation as default
        if i == 0:
            variation["default"] = True

        variations.append(variation)
    return variations

# Function to create or update a product in WooCommerce
def create_or_update_product(product_data):
    # Determine if product is simple or variable
    is_variable = len(product_data["variants"]) > 1
    # Parent product details
    parent_weight = product_data["variants"][0].get("grams", 0) / 1000
    parent_image = product_data["images"][0]["src"] if product_data.get("images") else None
    product = {
        "name": product_data["title"],
        "type": "variable" if is_variable else "simple",
        "sku": "" if product_data.get("handle") is None else product_data["variants"][0].get("sku", str(product_data["id"])) if not is_variable else "",  # Set SKU to empty string if handle is None
        "regular_price": product_data["variants"][0]["price"] if not is_variable else "",
        "description": product_data["body_html"],
        "short_description": "",
        "slug": product_data["handle"] if product_data.get("handle") else product_data["title"].lower().replace(" ", "-"),  # Use title as slug if handle is None
        "images": [{"src": clean_image_url(img["src"])} for img in product_data["images"]],
        "meta_data": [{"key": "_vendor", "value": product_data["vendor"]}],
        "weight": str(parent_weight)
    }

    if is_variable:
        product["attributes"] = create_attributes(product_data)

    # Check if the product already exists
    if product["sku"]:
        response = wcapi.get("products", params={"sku": product["sku"]})
    else:
        response = wcapi.get("products", params={"slug": product["slug"]})
    existing_products = response.json()

    if existing_products:
        product_id = existing_products[0]["id"]
        response = wcapi.put(f"products/{product_id}", product)
    else:
        response = wcapi.post("products", product)

    if response.status_code in [200, 201]:
        product_id = response.json().get("id")
        if is_variable:
            variations = create_variations(product_data, parent_weight, parent_image)
            for variation in variations:
                variation_response = wcapi.post(f"products/{product_id}/variations", variation)
                if variation_response.status_code in [200, 201]:
                    print(f"Variation added successfully. SKU: {variation['sku']}")
                else:
                    print(f"Failed to create variation for product {product_data['title']}: {variation_response.json()}")
        print(f"Product {product_data['title']} created/updated successfully.")
    else:
        print(f"Failed to create/update product {product_data['title']}: {response.json()}")

# Import products
for product_data in shopify_data["products"]:
    create_or_update_product(product_data)

