<div id="modal_theme_primary" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            @if (request()->route()->hasParameter('rp_id') && ! is_null(auth()->user()->retailerUser->retailer->retailerProducts()->where('id', decode($rp_id))->first()->content))
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title">
                        {{ auth()->user()->retailerUser->retailer->retailerProducts()->where('id', decode($rp_id))->first()->content->title }}
                    </h6>

                </div>

                <div class="modal-body text-justify">
                    {!! Html::image(auth()->user()->retailerUser->retailer->retailerProducts()->where('id', decode($rp_id))->first()->content->file, '') !!}

                    {!! auth()->user()->retailerUser->retailer->retailerProducts()->where('id', decode($rp_id))->first()->content->content !!}
                </div>
            @endif

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>