<!-- Main navbar -->
@include('layouts.navbar')

<!-- /main navbar -->


<!-- Page content -->
<div class="page-content">
    <!-- /main sidebar -->


    <!-- Main content -->
		<div class="content-wrapper">

			<!-- Inner content -->
			<div class="content-inner">

				<!-- Content area -->
				<div class="content d-flex justify-content-center align-items-center">

					<!-- Container -->
					<div class="flex-fill">

						<!-- Error title -->
						<div class="text-center mb-4">
							<img src="{{asset('template/assets/images/error_bg.svg')}}" class="img-fluid mb-3" height="230" alt="">
							<h1 class="display-3 fw-semibold lh-1 mb-3">404</h1>
							<h6 class="w-md-25 mx-md-auto">Oops, error. <br> Halaman tidak ditemukan</h6>
						</div>
						<!-- /error title -->


						<!-- Error content -->
						<div class="text-center">
							<a href="{{route('dashboard')}}" class="btn btn-primary">
								<i class="ph-house me-2"></i>
								Kembali ke berandan
							</a>
						</div>
						<!-- /error wrapper -->

					</div>
					<!-- /container -->

				</div>
				<!-- /content area -->

			</div>
			<!-- /inner content -->

		</div>
		<!-- /main content -->

</div>

            @include('layouts.footer')

        </div>
        <!-- /inner content -->

    </div>
    <!-- /main content -->

</div>

<!-- /page content -->


<!-- Notifications -->
@include('layouts.components.notifications')

<!-- /notifications -->


<!-- Demo config -->
@include('layouts.components.demo_config')

<!-- /demo config -->

</body>



</html>
