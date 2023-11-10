<?php $date = Carbon\Carbon::yesterday()->format('Y-m-d'); ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">


<url>
   <loc>{{ url('/') }}</loc>
   <lastmod>{{$date}}</lastmod>
   <priority>1.0</priority>
</url>
<url>
   <loc>{{ url('/') }}/login</loc>
   <lastmod>{{$date}}</lastmod>
   <priority>1.0</priority>
</url>
<url>
   <loc>{{ url('/') }}/register</loc>
   <lastmod>{{$date}}</lastmod>
   <priority>1.0</priority>
</url>

@foreach(\App\Models\Help::where('menu', 'no')->get() as $help)
   <url>
      <loc>{{ url('/') }}/help/{{ $help->slug }}</loc>
      <lastmod>{{$date}}</lastmod>
      <priority>1.0</priority>
   </url>
@endforeach   
</urlset>
