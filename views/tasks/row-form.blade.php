@php
    $formName = 'task' . $task->id;
@endphp
<tr id="{{ $formName }}_row" hx-swap="outerHTML" hx-target="this">
    <td>{{ $task->id }}</td>
    <td>
        {{--status--}}
        <label class="sr-only" for="{{ $formName }}_status">{{ __('scraper::global.status') }}</label>
        <select class="form-control" name="status" id="{{ $formName }}_status">
            @foreach($statuses as $item)
                <option {{ old('status', $task->status) == $item->value ? 'selected' : '' }} value="{{ $item->value }}">{{  $item->name }}</option>
            @endforeach
        </select>
        @error('status')
        <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </td>

    <td>
        {{--script--}}
        <label class="sr-only" for="{{ $formName }}_script">{{ __('scraper::global.script') }}</label>
        <select class="form-control" name="script" id="{{ $formName }}_script">
            @foreach($actionsClasses as $key => $value)
                <option {{ old('script', $task->script) == $key ? 'selected' : '' }} value="{{ $key }}">{{ $key }}</option>
            @endforeach
        </select>
        @error('script')
        <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </td>

    <td>
        {{--url--}}
        <label class="sr-only" for="{{ $formName }}_url">{{ __('scraper::global.url') }}</label>
        <input type="text" class="form-control flex-grow-1" name="url" id="{{ $formName }}_url"
               value="{{ old('url', $task->url) }}">
        @error('url')
        <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </td>

    <td>
        <div class="d-flex" style="gap: 0.15rem;">
            {{--site_content_id--}}
            <label class="sr-only" for="{{ $formName }}_site_content_id">{{ __('scraper::global.document_id') }}</label>
            <input type="number" class="form-control flex-grow-1" name="site_content_id"
                   id="{{ $formName }}_site_content_id"
                   value="{{ old('site_content_id', $task->site_content_id) ?? 0 }}">

            {{--template--}}
            <label class="sr-only" for="{{ $formName }}_template">{{ __('scraper::global.template') }}</label>
            <select class="form-control" name="template" id="{{ $formName }}_template">
                @foreach($templates as $item)
                    <option {{ old('site_content_template', $task->site_content_template) == $key ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->templatename }}</option>
                @endforeach
            </select>
        </div>
        @error('site_content_id')
        <small class="form-text text-danger">{{ $message }}</small>
        @enderror
        @error('template')
        <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </td>

    <td>
        {{--timestamp--}}
        <label class="sr-only" for="{{ $formName }}_timestamp">{{ __('scraper::global.processing_date') }}</label>
        <input class="form-control" type="datetime-local" name="timestamp"
               id="{{ $formName }}_timestamp"
               value="{{ old('timestamp', $task->timestamp) }}">
        @error('timestamp')
        <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </td>

    <td>{{ $task->created_at->diffForHumans() }}</td>

    <td>
        {{--submit button--}}
        {{ csrf_field() }}
        <button hx-put="{{ route('scraper::tasks.update', $task) }}" hx-include="closest tr"
                class="btn btn-success w-100" type="submit">{{ __('scraper::global.submit') }}
        </button>
    </td>
</tr>

