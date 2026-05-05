<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<!--begin::Head-->
	<head>
	    <base href="/">
	    <title>{{ env('APP_NAME') }}</title>
		<meta charset="utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
    	<link rel="icon" href="/assets/img/favicon.png" type="image/x-icon">
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
	    <!--begin::Page Custom Stylesheets(used by this page)-->
		<link href="/assets/css/custom.css?v20126.03.29.02.45" rel="stylesheet" type="text/css" />
	    <!--end::Page Custom Stylesheets-->
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="app-blank bgi-size-cover bgi-attachment-fixed bgi-position-center bgi-no-repeat">
		<!--begin::Theme mode setup on page load-->
		<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
		<!--end::Theme mode setup on page load-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root" id="kt_app_root">
			<!--begin::Authentication - Sign-in -->
			<div class="d-flex flex-column flex-column-fluid flex-lg-row">
				<!--begin::Aside-->
				<div class="d-flex flex-center w-lg-50 pt-15 pt-lg-0 px-10" style="background-color:#009688;">
					<!--begin::Aside-->
					<div class="d-flex flex-column-fluid flex-lg-start flex-column">
						<!--begin::Title-->
						<div class="m-auto symbol symbol-circle symbol-lg-75">
							<img alt="Logo" src="/assets/img/shop.jpg" class="h-300px img-responsive" />
						</div>
						<div class="m-auto">
							<h1 class="text-white mt-10">Gestion Shop de Marius</h1>
						</div>
						<!--end::Title-->
					</div>
					<!--begin::Aside-->
				</div>
				<!--begin::Aside-->
				<!--begin::Body-->
				<div class="d-flex flex-center w-lg-50" style="background-color:#F9F5F5BD;">
					<!--begin::Card-->
					<div class="bg-body d-flex flex-column align-items-stretch flex-center rounded-4 w-md-500px p-20">
						<!--begin::Wrapper-->
						<div class="d-flex flex-center flex-column flex-column-fluid">
							<!--begin::Form-->
							<form class="form w-100" novalidate="novalidate" id="kt_sign_in_form">
        						@csrf
								<!--begin::Heading-->
								<div class="text-center">
									<!--begin::Title-->
									<h1 class="title-signin">Connexion</h1>
									<!--end::Title-->
								</div>
								<!--begin::Heading-->
								<!--begin::Heading-->
								<div class="text-center">
									<!--begin::Subtitle-->
									<div class="subtitle-signin">Accédez à votre espace de gestion.</div>
									<!--end::Subtitle=-->
								</div>
								<!--begin::Heading-->
								<!--begin::Input group=-->
								<div class="fv-row mb-8">
									<!--begin::Login-->
									<input type="text" name="login" placeholder="Login" class="form-control bg-transparent" />
									<!--end::Login-->
								</div>
								<!--end::Input group=-->
								<div class="fv-row mb-3 position-relative">
									<!--begin::Password-->
									<input type="password" name="password" placeholder="Mot de passe" class="form-control bg-transparent" />
                                	<i class="fa fa-eye-slash viewPass"></i>
									<!--end::Password-->
								</div>
								<!--end::Input group=-->
								<!--begin::Wrapper-->
								<div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold">
									<div></div>
									<!--begin::Link-->
									<a href="/forgotpass" class="subtitle-signin">Mot de passe oublié ?</a>
									<!--end::Link-->
								</div>
								<!--end::Wrapper-->
								<!--begin::Submit button-->
								<div class="d-grid">
									<button type="submit" id="kt_sign_in_submit" class="btn-signin">
										<!--begin::Indicator label-->
										<span class="indicator-label">Se connecter</span>
										<!--end::Indicator label-->
										<!--begin::Indicator progress-->
										<span class="indicator-progress">Veillez patienter...
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
										<!--end::Indicator progress-->
									</button>
								</div>
                        		<div class="d-grid error"></div>
								<!--end::Submit button-->
							</form>
							<!--end::Form-->
						</div>
						<!--end::Wrapper-->
					</div>
					<!--end::Card-->
				</div>
				<!--end::Body-->
			</div>
			<!--end::Authentication - Sign-in-->
		</div>
		<!--end::Root-->
		<!--begin::Javascript-->
		<script>var hostUrl = "/assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="/assets/plugins/global/plugins.bundle.js"></script>
		<script src="/assets/js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="/assets/js/custom/authentication/sign-in/general.js"></script>
		<script src="/assets/js/custom/icheck.js"></script>
		<script src="/assets/js/custom.js?v20126.03.29.02.45"></script>
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>