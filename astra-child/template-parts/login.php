<?php
/* Template Name: Login Template */
get_header(); ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header text-center bg-primary text-white">
                    <h3 class="mb-0">Login</h3>
                </div>
                <div class="card-body">
                    <form id="ajax-login-form" action="#" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username or Email Address</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username or email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" id="submit" class="btn btn-primary w-100">Login</button>
                        </div>
                        <div id="login-result" class="mt-3"></div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <small>
                        Don't have an account? <a href="<?php echo site_url('/register/'); ?>" class="text-primary">Register here</a>.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
