@php header("Content-type: text/xml"); @endphp
<?xml version="1.0" encoding="UTF-8"?>
	<articles>
		<UUID>{{ $items[0]['uuID'] }}</UUID> 
		<time>{{ $items[0]['uuTime'] }}</time>
		 @foreach($items as $key=>$item)
		 <article>
			<ID>{{ $item['id'] }}</ID> 
			<nativeCountry>TH</nativeCountry>
			<language>th</language>
			<startYmdtUnix>{{ strtotime($item['start_publish']) }}000</startYmdtUnix> 
            <endYmdtUnix>{{ (strtotime($item['start_publish']) + 90 * 24 * 60 * 60) }}000</endYmdtUnix> 
			<title>{{ htmlspecialchars($item['title'],ENT_XML1, 'UTF-8') }}</title>
			<category>{{ $item['program']['category']['name'] }}</category>
			<publishTimeUnix>{{ strtotime($item['start_publish']) }}000</publishTimeUnix>
			<updateTimeUnix>{{ strtotime($item['updated_at']) }}000</updateTimeUnix>
			<contentType>5</contentType>
			<contents>
				<video>
					<title>{{ htmlspecialchars($item['title'],ENT_XML1, 'UTF-8') }}</title>
					<description>{{ htmlspecialchars($item['tagline'],ENT_XML1, 'UTF-8') }}</description>
					<url>{{ $item['mp4_media_url'] }}</url>
					<thumbnail>{{ $item['display_image']['sizes']['large']['url'] }}</thumbnail> 
				</video>
				<text>
					<content><![CDATA[{{ $item['description'] }}]]></content>
				</text>
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
			<sourceUrl>{{ $item['canonical_url'] }}</sourceUrl> 
		 </article>
		 @endforeach
	</articles>