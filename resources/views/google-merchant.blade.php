<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0"
     xmlns:g="http://base.google.com/ns/1.0">
<channel>
    <title>Berd Vay'e Sculptures</title>
    <link>{{ url('/') }}</link>
    <description>Google Merchant product feed</description>

@foreach ($products as $product)
    <item>
        <g:id>{{ $product->id }}</g:id>
        <g:title><![CDATA[{{ $product->model_name }}{{ $product->size ? ' - ' . $product->size : '' }}]]></g:title>
        <g:description><![CDATA[{{ strip_tags($product->description) }}]]></g:description>
        <g:link>{{ url('/sculptures/' . strtolower(str_replace(' ', '-', $product->model_name))) }}</g:link>
        <g:image_link>https://berdvaye.com/images/gallery/thumbnail/{{ strtolower($product->p_model) }}_thumb.jpg</g:image_link>
        <g:availability>in_stock</g:availability>
        <g:price>{{ number_format($product->p_retail, 2) }} USD</g:price>
        <g:condition>new</g:condition>
        <g:brand>Berd Vay'e</g:brand>
        <g:product_type><![CDATA[Art > Sculpture]]></g:product_type>
    @if ($product->size)
    <g:size><![CDATA[{{ $product->size }}]]></g:size>
    @endif
    @if ($product->weight)
    <g:shipping_weight>{{ $product->weight+3 }} lb</g:shipping_weight>
    @endif
    <g:google_product_category>Home &amp; Garden &gt; Decor &gt; Artwork &gt; Sculptures &amp; Statues</g:google_product_category>
    </item>
@endforeach

</channel>
</rss>
