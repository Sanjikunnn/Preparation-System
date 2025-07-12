<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Preparation System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
  * {
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

  body {
    margin: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: url('https://e1.pxfuel.com/desktop-wallpaper/22/549/desktop-wallpaper-erp-technology-value-matrix-analysis-2018-terillium-erp.jpg') no-repeat center center fixed;
    background-size: cover;
  }

  .login-card {
    background: rgba(255, 255, 255, 0.6); /* soft glass effect */
    padding: 2rem 2.5rem;
    border-radius: 16px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    width: 100%;
    max-width: 420px;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    color: #222;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.3);
  }

  .login-card h2 {
    text-align: center;
    margin-bottom: 1.5rem;
    font-weight: 600;
    color: #222;
  }

  label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #444;
    font-size: 0.95rem;
  }

  input[type="text"],
  input[type="password"] {
    width: 100%;
    padding: 0.75rem;
    margin-bottom: 1.3rem;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 1rem;
    background-color: #fff;
    transition: border-color 0.3s ease;
  }

  input[type="text"]:focus,
  input[type="password"]:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.2);
  }

  button {
    width: 100%;
    padding: 0.85rem;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
  }

  button:hover {
    background-color: #0056b3;
    transform: translateY(-1px);
  }

  .alert {
    padding: 12px 16px;
    margin-bottom: 1rem;
    border-radius: 6px;
    font-size: 0.95rem;
    text-align: center;
  }

  .alert-success {
    background-color: #d1e7dd;
    color: #0f5132;
    border: 1px solid #badbcc;
  }

  .alert-error {
    background-color: #f8d7da;
    color: #842029;
    border: 1px solid #f5c2c7;
  }

  @media (max-width: 500px) {
    .login-card {
      padding: 1.5rem;
      border-radius: 12px;
    }
  }
</style>

</head>
<body>

  <div class="login-card">
    <h2>Login Preparation</h2>

    @if (session('error'))
      <div class="alert alert-error">
        {{ session('error') }}
      </div>
    @endif

    @if (session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
      @csrf
      <label for="nik">NIK:</label>
      <input type="text" name="nik" id="nik" placeholder="Masukan NIK Anda" autocomplete="off" required>

      <label for="password">Password:</label>
      <input type="password" name="password" id="password" placeholder="Masukan Password Anda" autocomplete="off" required>

      <button type="submit">Masuk</button>
    </form>
  </div>

</body>
</html>

<script>
  setTimeout(() => {
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) successAlert.remove();

    const errorAlert = document.querySelector('.alert-error');
    if (errorAlert) errorAlert.remove();
  }, 4000); // 4 detik
</script>
