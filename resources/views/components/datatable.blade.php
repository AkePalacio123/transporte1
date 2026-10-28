<div class="card shadow-sm">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="fw-bold mb-0">
                    {{ $title }}
                </h3>

                @isset($subtitle)

                    <small class="text-muted">

                        {{ $subtitle }}

                    </small>

                @endisset

            </div>

            {{ $actions }}

        </div>

        {{ $filters }}

        <div class="table-responsive">

            {{ $slot }}

        </div>

    </div>

</div>