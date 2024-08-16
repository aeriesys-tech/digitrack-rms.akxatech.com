<template>
    <div class="page-sign">
        <div class="card card-sign">
      <div class="card-header">
        <a href="../index.html" class="header-logo mb-4">dashbyte</a>
        <h3 class="card-title">Sign Up</h3>
        <p class="card-text">It's free to signup and only takes a minute.</p>
      </div><!-- card-header -->
      <div class="card-body">
        <div class="mb-3">
          <label class="form-label">Email address</label>
          <input type="text" class="form-control" placeholder="Enter your email address">
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" class="form-control" placeholder="Enter your password">
        </div>
        <div class="mb-3">
          <label class="form-label">Full name</label>
          <input type="text" class="form-control" placeholder="Enter your full name">
        </div>
        <div class="mb-4">
          <small>By clicking <strong>Create Account</strong> below, you agree to our terms of service and privacy statement.</small>
        </div>
        <button class="btn btn-primary btn-sign">Create Account</button>

       
      </div><!-- card-body -->
      <div class="card-footer">
        Already have an account? <router-link to="/">Sign In</router-link>
      </div><!-- card-footer -->
    </div><!-- card -->
    </div>
</template>