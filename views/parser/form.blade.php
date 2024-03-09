<fieldset style="display: flex;flex-direction: column;" class="mb-3">
    <div class="form-group">
        <label for="parser_script">{{ __('scraper::global.script') }}</label>
        <select class="form-control" name="script" id="parser_script">
            @foreach($actionsClasses as $key => $value)
                <option @if(session()->get('scraper__script') == $key) selected @endif value="{{ $key }}">{{ $key }}</option>
            @endforeach
        </select>
        @error('script')
        <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label for="parser_urls">{{ __('scraper::global.urls') }}</label>
        <textarea class="form-control" name="urls" id="parser_urls" rows="4">{{ old('urls') }}</textarea>
        @error('urls')
        <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </div>
</fieldset>


<div class="form-group">
    <label for="parser_parent_id">{{ __('scraper::global.parent_document') }}</label>
    <input class="form-control" type="number" name="parent_id" id="parser_parent_id"
           value="{{ old('parent_id', session()->get('scraper__parent_id', 0)) }}">
    @error('parent_id')
    <small class="form-text text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label for="parser_template">{{ __('scraper::global.template') }}</label>
    <select class="form-control" name="template" id="parser_template">
        @foreach($templates as $item)
            <option value="{{ $item->id }}">{{ $item->templatename }}</option>
        @endforeach
    </select>
    @error('template')
    <small class="form-text text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label for="parser_timestamp">{{ __('scraper::global.processing_date') }}</label>
    <input class="form-control" type="datetime-local" name="timestamp"
           id="parser_timestamp" value="{{ now()->addSeconds(evo()->getConfig('server_offset_time'))->format('Y-m-d\TH:i') }}">
    @error('timestamp')
    <small class="form-text text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <div class="form-check form-check-inline">
        <input name="publish" class="form-check-input" type="checkbox"
               id="parser_publish" {{ session()->get('scraper__publish') ? 'checked' : '' }}>
        <label class="form-check-label" for="parser_publish">{{ __('scraper::global.publish') }}</label>
    </div>
</div>
