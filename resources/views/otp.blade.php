<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Purple Admin | Verifikasi OTP</title>

  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">

  <!-- Layout styles -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

  <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
<style>
  .otp-container {
    display: flex;
    justify-content: center;
    gap: 12px;
  }

  .otp-input {
    width: 55px;
    height: 60px;
    font-size: 24px;
    font-weight: 600;
    text-align: center;
    border-radius: 10px;
    border: 1px solid #ced4da;
    padding: 0;
    line-height: 60px; /* bikin angka center vertikal */
  }

  .otp-input:focus {
    border-color: #b66dff;
    box-shadow: 0 0 6px rgba(182,109,255,0.4);
    outline: none;
  }

  /* hilangkan panah number input */
  .otp-input::-webkit-outer-spin-button,
  .otp-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }
</style>


</head>

<body>
<div class="container-scroller">
  <div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-center auth">
      <div class="row flex-grow">
        <div class="col-lg-4 mx-auto">

          <div class="auth-form-light text-left p-5">

            <div class="brand-logo text-center">
              <!-- logo optional -->
              <!-- <img src="{{ asset('assets/images/logo.svg') }}"> -->
            </div>

            <h4 class="text-center">Verifikasi OTP</h4>

            <h6 class="font-weight-light text-center mb-4">
              Masukkan 6 digit kode OTP
            </h6>

            <form method="POST" action="/otp">
              @csrf

              <!-- OTP BOXES -->
              <div class="form-group">
                <div class="otp-container">

                    <input type="number" inputmode="numeric" maxlength="1" class="otp-input" required>
                    <input type="number" inputmode="numeric" maxlength="1" class="otp-input" required>
                    <input type="number" inputmode="numeric" maxlength="1" class="otp-input" required>
                    <input type="number" inputmode="numeric" maxlength="1" class="otp-input" required>
                    <input type="number" inputmode="numeric" maxlength="1" class="otp-input" required>
                    <input type="number" inputmode="numeric" maxlength="1" class="otp-input" required>
                </div>
              </div>

              <!-- hidden input gabungan -->
              <input type="hidden" name="otp" id="otp">

              <!-- BUTTON -->
              <div class="mt-4 d-grid gap-2">
                <button type="submit"
                  class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                  Verifikasi OTP
                </button>
              </div>

              <!-- error -->
              @if(session('error'))
                <div class="text-danger mt-3 text-center">
                  {{ session('error') }}
                </div>
              @endif

            </form>

            <div class="text-center mt-4">
              <a href="{{ route('login') }}">Kembali ke login</a>
            </div>

          </div>

        </div>
      </div>
    </div>
  </div>
</div>


<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>

<script>
const inputs = document.querySelectorAll(".otp-input");
const hiddenInput = document.getElementById("otp");

inputs.forEach((input, index) => {

  input.addEventListener("input", () => {

    if (input.value.length === 1 && index < inputs.length - 1) {
      inputs[index + 1].focus();
    }

    updateOTP();

  });

  input.addEventListener("keydown", (e) => {

    if (e.key === "Backspace" && input.value === "" && index > 0) {
      inputs[index - 1].focus();
    }

  });

});

function updateOTP() {

  let otp = "";

  inputs.forEach(input => {
    otp += input.value;
  });

  hiddenInput.value = otp;

}
</script>

</body>
</html>
