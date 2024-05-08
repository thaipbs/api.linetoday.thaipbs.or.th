@php header("Content-type: text/xml"); @endphp
<?xml version="1.0" encoding="UTF-8"?>
	<articles>
		<UUID>{{ !empty($items[0]['isNews']) ? '01' : '02' }}{{ $items[0]['id'] }}</UUID> 
		<time>{{ strtotime($items[0]['start_publish']) }}000</time>
		 @foreach($items as $key=>$item)
		 <article>
			<ID>{{ $item['id'] }}</ID> 
			<nativeCountry>TH</nativeCountry>
			<language>th</language>
			<startYmdtUnix>{{ strtotime($item['start_publish']) }}000</startYmdtUnix> 
            <endYmdtUnix>{{ (strtotime($item['start_publish']) + 90 * 24 * 60 * 60) }}000</endYmdtUnix> 
			<title>{{ htmlspecialchars($item['title'],ENT_XML1, 'UTF-8') }}</title>
			<category>{{ !empty($item['isNews']) ? $item['categoryName'] : $item['program']['category']['name'] }}</category>
			<subCategory>{{ !empty($item['isNews']) ? 'ข่าว' : $item['program']['title'] }}</subCategory>
			<publishTimeUnix>{{ strtotime($item['start_publish']) }}000</publishTimeUnix>
			<updateTimeUnix>{{ strtotime($item['updated_at']) }}000</updateTimeUnix>
			<contentType>{{ !empty($item['isNews']) ? 0 : 5 }}</contentType>
			<thumbnail>{{ !empty($item['isNews']) ? $item['media']['default'] : $item['display_image']['sizes']['large']['url'] }}</thumbnail>
			<contents>
			@if(!empty($item['isNews']))
				<image>
					<title>{{ htmlspecialchars($item['title'],ENT_XML1, 'UTF-8') }}</title> 
					<description></description> 
					<url>{{ $item['media']['default'] }}</url> 
					<thumbnail>{{ $item['media']['default'] }}</thumbnail> 
				</image>
				<text>
					<content><![CDATA[{{ str_replace("src=\"/media/", "https://news.thaipbs.or.th/media/", $item['content']) }}]]></content>
				</text>
			@else
				<image>
					<title>{{ htmlspecialchars($item['title'],ENT_XML1, 'UTF-8') }}</title> 
					<description></description> 
					<url>{{ $item['display_image']['sizes']['large']['url'] }}</url> 
					<thumbnail>{{ $item['display_image']['sizes']['large']['url'] }}</thumbnail> 
				</image>
				<video>
					<title>{{ htmlspecialchars($item['title'],ENT_XML1, 'UTF-8') }}</title>
					<description>{{ htmlspecialchars($item['tagline'],ENT_XML1, 'UTF-8') }}</description>
					<url>{{ $item['mp4_media_url'] }}</url>
					<thumbnail>{{ $item['display_image']['sizes']['large']['url'] }}</thumbnail> 
					<restrictedCountry></restrictedCountry>  
					<width></width>
					<height></height>
				</video>
				<text>
					<content><![CDATA[{{ $item['description'] }}]]></content>
				</text>
			@endif
			</contents>
			@php
				$other = $items;
				unset($other[$key]);
				$recommend = array_rand($other , 3);
			@endphp
			<recommendArticles>
			@foreach($recommend as $row)
				<article>
					<title>{{ htmlspecialchars($other[$row]['title'],ENT_XML1, 'UTF-8') }}</title> 
					<url>{{ !empty($other[$row]['isNews']) ? $other[$row]['canonical'] : $other[$row]['canonical_url'] }}</url> 
					<thumbnail>{{ !empty($other[$row]['isNews']) ? $other[$row]['media']['default'] : $other[$row]['display_image']['sizes']['large']['url'] }}</thumbnail> 
				</article>
			@endforeach
			</recommendArticles>
			<author>Thai PBS</author>
			<sourceUrl>{{ !empty($item['isNews']) ? $item['canonical'] : $item['canonical_url'] }}</sourceUrl> 
			@if(!empty($item['tags']))
			<tags>
				@foreach($item['tags'] as $tag)
					@if($tag <> "")
						<tag>{{ $tag }}</tag>
					@endif
				@endforeach
			</tags>
			@endif
		 </article>
		 @endforeach
	</articles>