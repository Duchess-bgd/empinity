<!DOCTYPE html>
<html>
<head>
    <title>Laravel API</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 4px; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 4px; overflow-x: auto; }
        .endpoint { margin-bottom: 20px; }
    </style>
</head>
<body>
<h1>Laravel API Server</h1>
<p>API is running. Use the following endpoints:</p>

<div class="endpoint">
    <h3>GET /api/categories</h3>
    <pre>curl http://impinity.test/api/categories</pre>
</div>

<div class="endpoint">
    <h3>GET /api/products</h3>
    <pre>curl http://impinity.test/api/products</pre>
    <p>With filters: <code>/api/products?search=laptop</code> or <code>/api/products?category_id=1</code></p>
</div>

<div class="endpoint">
    <h3>POST /api/products</h3>
    <pre>
curl -X POST http://impinity.test/api/products \
  -H "Content-Type: application/json" \
  -d '{"name":"New Product","category_id":1,"price":99.99,"stock":10}'
        </pre>
</div>

<div class="endpoint">
    <h3>PUT /api/products/{id}</h3>
    <pre>
curl -X PUT http://impinity.test/api/products/1 \
  -H "Content-Type: application/json" \
  -d '{"price":129.99,"stock":15}'
        </pre>
</div>

<div class="endpoint">
    <h3>DELETE /api/products/{id}</h3>
    <pre>curl -X DELETE http://impinity.test/api/products/1</pre>
</div>
</body>
</html>
