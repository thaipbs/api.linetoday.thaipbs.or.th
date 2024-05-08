@php header("Content-type: text/xml"); @endphp
<?xml version="1.0" encoding="UTF-8"?>
	<articles>
		<UUID>{{ $items[0]['uuID'] }}1305</UUID> 
		<time>{{ $items[0]['uuTime'] }}</time>
		 @foreach($items as $key=>$item)
		 <article>
			<ID>{{ $item['id'] }}</ID> 
			<nativeCountry>TH</nativeCountry>
			<language>th</language>
			<startYmdtUnix>{{ strtotime($item['publishTime']) }}000</startYmdtUnix> 
			<endYmdtUnix>{{ (strtotime($item['publishTime']) + 90 * 24 * 60 * 60) }}000</endYmdtUnix> 
			<title>{{ htmlspecialchars($item['title'],ENT_XML1, 'UTF-8') }}</title>
			<category>{{ !empty($item['tags']) && in_array('COVID-19', $item['tags']) ? 'โควิด 19' : 'ข่าว'.$item['categoryName'] }}</category>
			<subCategory>ข่าว</subCategory>
			<publishTimeUnix>{{ strtotime($item['publishTime']) }}000</publishTimeUnix>
			<updateTimeUnix>{{ strtotime($item['lastUpdateTime']) }}000</updateTimeUnix>
			<contentType>0</contentType>
			<thumbnail>{{ $item['media']['default'] }}</thumbnail>
			<contents>
				<image>
					<title>{{ htmlspecialchars($item['title'],ENT_XML1, 'UTF-8') }}</title> 
					<description></description> 
					<url>{{ $item['media']['default'] }}</url> 
					<thumbnail>{{ $item['media']['default'] }}</thumbnail> 
				</image>
				<text>
					<content><![CDATA[{{ str_replace("src=\"/media/", "https://news.thaipbs.or.th/media/", $item['content']) }}]]></content>
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
					<url>{{ $other[$row]['canonical'] }}</url> 
					<thumbnail>{{ $other[$row]['media']['default'] }}</thumbnail> 
				</article>
			@endforeach
			</recommendArticles>
			<author>Thai PBS</author>
			<sourceUrl>{{ $item['canonical'] }}</sourceUrl> 
			@if(!empty($item['tags']))
			<tags>
				@foreach($item['tags'] as $tag)
					@if($tag <> "")
						<tag>{{ mb_substr(str_replace(array('(',')'),'',trim(trim($tag, '"'),'“')),0,29,'UTF-8') }}</tag>
					@endif
				@endforeach
			</tags>
			@endif
		 </article>
		 @endforeach
	</articles>