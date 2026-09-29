{{-- Expects: $prefix (og|twitter), $title, $hint, $current (resolved URL or null), $externalUrl --}}
<div class="rounded-xl border border-slate-200 p-4">
    <p class="{{ $label }}">{{ $title }}</p>
    <div class="flex flex-col gap-4 sm:flex-row">
        <div class="flex h-24 w-full flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-slate-100 sm:w-44">
            @if($current)
                <img src="{{ $current }}" alt="{{ $title }}" class="h-full w-full object-cover">
            @else
                <i class="fa-regular fa-image text-2xl text-slate-300"></i>
            @endif
        </div>
        <div class="flex-1 space-y-2">
            <input type="file" id="{{ $prefix }}_image_file" name="{{ $prefix }}_image_file" accept="image/jpeg,image/png,image/webp"
                   class="block w-full text-xs text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-amber-50 file:px-3 file:py-2 file:text-xs file:font-bold file:text-amber-700 hover:file:bg-amber-100">
            @error($prefix . '_image_file') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
            <input type="url" id="{{ $prefix }}_image_url" name="{{ $prefix }}_image_url" maxlength="500"
                   value="{{ old($prefix . '_image_url', $externalUrl) }}" placeholder="…or paste an image URL (https://)"
                   class="{{ $input }} {{ $border($prefix . '_image_url') }} py-2 text-xs">
            @error($prefix . '_image_url') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
            <p class="text-[11px] text-slate-400">{{ $hint }}</p>
            @if($current)
                <label class="inline-flex items-center gap-2 text-xs font-semibold text-red-600">
                    <input type="checkbox" id="remove_{{ $prefix }}_image" name="remove_{{ $prefix }}_image" value="1" @checked(old('remove_' . $prefix . '_image')) class="h-3.5 w-3.5 rounded border-slate-300">
                    Remove current image
                </label>
            @endif
        </div>
    </div>
</div>
