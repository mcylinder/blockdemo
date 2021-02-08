<hr>
<div class="content__actions">
    <div class="dropdown dropdown--arrow dropdown--top dropdown--center">
        <button id="validate_blocks" data-v-2c3d97ec="" type="button" class="button button--small button--action">
            Validate blocks
        </button>
    </div>
</div>
<p>
<pre id="validate_results" class="f--tiny"></pre></p>
@push('extra_js')
    <script>
        function validate_blocks() {
            document.getElementById("validate_results").innerHTML = 'checking..';
            let activeFields = [];
            let currentBlocks = window.TWILL.STORE.form.blocks;
            currentBlocks.forEach(function (dom) {
                activeFields.push(dom.type);
                console.log(dom.type);
            });
            fetch('/block_validate?' + new URLSearchParams({
                'model': '{!! $modelName !!}', 'fields': activeFields,
            }))
                .then((resp) => resp.json())
                .then(function (data) {
                    console.log(data);
                    document.getElementById("validate_results").innerHTML = JSON.stringify(data, null, 4);
                });
        }

        document.getElementById("validate_blocks").addEventListener("click", function () {
            validate_blocks();
        });

    </script>
@endpush
