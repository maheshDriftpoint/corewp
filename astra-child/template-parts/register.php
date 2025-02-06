<?php
/* Template Name: Registration Template */
get_header(); ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white text-center">
                    <h3 class="mb-0">Create an Account</h3>
                </div>
                <div class="card-body">
                    <form id="ajax-register-form" action="#" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Choose a username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Create a password" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" id="submit" class="btn btn-success w-100">Register</button>
                        </div>
                        <div id="register-result" class="mt-3"></div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <small>
                        Already have an account? <a href="<?php echo site_url('/login/'); ?>" class="text-success">Login here</a>.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
