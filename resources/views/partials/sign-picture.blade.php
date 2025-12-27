@php
	$size = isset($size) ? (int) $size : 80;
	$class = $class ?? '';
	$alt = $alt ?? '';
	$title = $title ?? null;
	$loading = $loading ?? 'lazy';
	$image = $image ?? '';
	$baseName = $image !== '' ? pathinfo($image, PATHINFO_FILENAME) : '';
	$webpPath = $baseName !== '' ? 'img/borden/' . $baseName . '.webp' : '';
	$avifPath = $baseName !== '' ? 'img/borden/' . $baseName . '.avif' : '';
	$webpExists = $webpPath !== '' && is_file(public_path($webpPath));
	$avifExists = $avifPath !== '' && is_file(public_path($avifPath));
@endphp

<picture>
	@if($avifExists)
		<source type="image/avif" srcset="{{ asset($avifPath) }}">
	@endif
	@if($webpExists)
		<source type="image/webp" srcset="{{ asset($webpPath) }}">
	@endif
	<img src="{{ asset('img/borden/'.$image) }}"
		class="{{ $class }}"
		width="{{ $size }}"
		height="{{ $size }}"
		alt="{{ $alt }}"
		@if($title) title="{{ $title }}" @endif
		loading="{{ $loading }}"
		decoding="async">
</picture>
