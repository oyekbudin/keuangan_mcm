@include('header')
@include('sidebar')
<!-- Main Content -->
<main class="main">
    <div class="main-content page-blank">
        <div class="blank-shell">
            <section class="section">
                <h5 class="section-title mb-3">Grid Form Layouts</h5>
                <div class="row g-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Admin layout</h5>
                            </div>
                            <div class="card-body">
                                <p>Header, sidebar, main content, and footer are already wired.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    @include('footer')
