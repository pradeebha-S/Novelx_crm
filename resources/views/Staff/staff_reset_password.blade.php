@extends('Staff.layout')

@section('content')
    <style>
        /* Center the card */
        .card {
            width: 400px;
            margin: 80px auto;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            text-align: center;
        }

        input.form-control {
            border-radius: 5px !important;
            border: 1px solid #33B02C1A;
        }

        /* Input group styling */
        .input-group {
            position: relative;
            width: 100%;
        }

        .input-group input {
            width: 100%;
            padding: 12px 45px;
            /* space for icons */
            border-radius: 10px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        /* Lock icon (left) */
        .input-group .lock-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
            font-size: 18px;
            pointer-events: none;
        }

        /* Eye icon (right) */
        .input-group .eye-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
            font-size: 18px;
            cursor: pointer;
        }

        /* Extra padding for gap after lock icon */
        input#a {
            padding-left: 40px;
            /* was 50px — increased by 10px for extra gap */
        }
    </style>

    <h4>Reset Password</h4>

   <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-4">
        <div class="card p-4 m-0">
          <form action="{{ route('staff_reset_password_form') }}" method="POST" id="login_form">
            @csrf
            <div>

              <div class="input-group">
                <span class="lock-icon menu-icon icon-base ti tabler-lock"></span>
                <input type="password" class="form-control" id="a" placeholder="Current Password" name="oldpass"
                  value="{{ old('oldpass') }}">
                <span class="eye-icon menu-icon icon-base ti tabler-eye"></span>
              </div>

              @error('oldpass')
                <small class="text-danger">{{ $message }}</small>
              @enderror

            </div>

            <div>

              <div class="input-group mt-6">
                <span class="lock-icon menu-icon icon-base ti tabler-lock"></span>
                <input type="password" class="form-control" id="a" placeholder="New Password" name="newpass"
                  value="{{ old('newpass') }}">
                <span class="eye-icon menu-icon icon-base ti tabler-eye"></span>

              </div>

              @error('newpass')
                <small class="text-danger">{{ $message }}</small>
              @enderror

            </div>
            <div>

              <div class="input-group mt-6">
                <span class="lock-icon menu-icon icon-base ti tabler-lock"></span>
                <input type="password" class="form-control" id="a" placeholder="Confirm Password" name="conpass"
                  value="{{ old('conpass') }}">
                <span class="eye-icon menu-icon icon-base ti tabler-eye"></span>

              </div>

              @error('cpass')
                <small class="text-danger">{{ $message }}</small>
              @enderror

            </div>

            <button class="btn btn-primary d-grid w-100 waves-effect waves-light mt-6" id="submit_btn">Reset
              Password</button>
          </form>
        </div>
      </div>
    </div>
  </div>


  <script>
    document.getElementById('submit_btn').addEventListener('click', function () {
      let btn = this;
      btn.disabled = true;
      btn.innerText = 'Processing...';
      document.getElementById('login_form').submit();
    });
    document.querySelectorAll('.eye-icon').forEach(icon => {

      icon.addEventListener('click', function () {

        let input = this.closest('.input-group').querySelector('input');

        if (input.type === "password") {
          input.type = "text";
          this.classList.remove("tabler-eye");
          this.classList.add("tabler-eye-off");
        } else {
          input.type = "password";
          this.classList.remove("tabler-eye-off");
          this.classList.add("tabler-eye");
        }
      });

    });

  </script>
@endsection