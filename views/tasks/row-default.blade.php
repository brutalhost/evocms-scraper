<tr id="task{{ $item->id }}_row" hx-target="this" hx-swap="outerHTML" @class([
                    'table-success' => $item->status->value == '1',
                    'table-secondary' => $item->status->value == '2',
                ])>
    <td>
        <label for="task{{ $item->id }}_mass_action" class="mb-0 d-flex align-items-center">
            <input type="checkbox" class="checkbox_mass_action" id="task{{ $item->id }}_mass_action"
                   name="mass_action[]" value="{{ $item->id }}" onchange="checkCheckboxes()"/>
            {{ $item->id }}</label>
    </td>
    <td>
        <span class="d-block">
            {{ $item->status->name }}
        </span>
        @isset($item->description)
            <span>
                <b>{{ __('scraper::global.error') }}:</b> {{ $item->description }}
            </span>
        @endisset
    </td>
    <td>{{ $item->script }}</td>
    <td><a class="text-truncate" href="{{ $item->url }}">{{ Str::limit($item->url, 50) }}</a></td>
    <td>
        @isset($item->site_content_id)
            <a href="/manager/index.php?a=27&id={{ $item->site_content_id }}">{{ $item->site_content_id }}</a>
        @endisset
    </td>
    <td class="text-truncate">{{ $item->timestamp }}</td>
    <td class="text-truncate">{{ $item->created_at->diffForHumans() }}</td>
    <td>
        <div class="d-flex" style="gap: 0.15rem;">
            <form class="flex-grow-1" action="{{ route('scraper::tasks.process', $item) }}"
                  method="post">
                @csrf
                <button class="btn btn-success w-100" type="submit"
                        data-tooltip="{{ __('scraper::global.to_process') }}"><i
                            class="fa fa-cloud-download"></i>
                </button>
            </form>
            <button hx-get="{{ route('scraper::tasks.get-form', $item) }}"
                    class="btn btn-info flex-grow-1" type="submit"
                    data-tooltip="{{ __('scraper::global.to_edit') }}"><i
                        class="fa fa-edit"></i>
            </button>
            <form class="flex-grow-1" action="{{ route('scraper::tasks.delete', $item) }}"
                  method="post">
                @csrf
                <button class="btn btn-danger w-100" type="submit"
                        data-tooltip="{{ __('scraper::global.to_delete') }}"><i
                            class="fa fa-trash-alt"></i>
                </button>
            </form>
        </div>
    </td>
</tr>
