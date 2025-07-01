<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="text-center">
                <h1 class="text-3xl font-bold text-teal-600 mb-2">SEA Catering</h1>
                <i class="fas fa-utensils text-teal-500 text-4xl mb-4"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Daftar Akun Baru
            </h2>
        </div>

        <div class="bg-white rounded-lg shadow-md p-8">
            <!-- Success/Error Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                        <span><?= session()->getFlashdata('success') ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 text-xl mr-3"></i>
                        <span><?= session()->getFlashdata('error') ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" action="/register" class="space-y-6">
                <?= csrf_field() ?>
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        Nama Lengkap
                    </label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        required
                        value="<?= old('name') ?>"
                        class="mt-1 appearance-none relative block w-full px-3 py-2 border <?= session()->getFlashdata('errors')['name'] ?? false ? 'border-red-500' : 'border-gray-300' ?> placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500 focus:z-10 sm:text-sm"
                        placeholder="Masukkan nama lengkap"
                    />
                    <?php if (session()->getFlashdata('errors')['name'] ?? false): ?>
                        <span class="text-red-500 text-sm mt-1"><?= session()->getFlashdata('errors')['name'] ?></span>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email
                    </label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        autocomplete="email"
                        required
                        value="<?= old('email') ?>"
                        class="mt-1 appearance-none relative block w-full px-3 py-2 border <?= session()->getFlashdata('errors')['email'] ?? false ? 'border-red-500' : 'border-gray-300' ?> placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500 focus:z-10 sm:text-sm"
                        placeholder="Masukkan email"
                    />
                    <?php if (session()->getFlashdata('errors')['email'] ?? false): ?>
                        <span class="text-red-500 text-sm mt-1"><?= session()->getFlashdata('errors')['email'] ?></span>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">
                        Nomor HP
                    </label>
                    <input
                        id="phone"
                        name="phone"
                        type="tel"
                        required
                        value="<?= old('phone') ?>"
                        class="mt-1 appearance-none relative block w-full px-3 py-2 border <?= session()->getFlashdata('errors')['phone'] ?? false ? 'border-red-500' : 'border-gray-300' ?> placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500 focus:z-10 sm:text-sm"
                        placeholder="08xxxxxxxxxx"
                    />
                    <?php if (session()->getFlashdata('errors')['phone'] ?? false): ?>
                        <span class="text-red-500 text-sm mt-1"><?= session()->getFlashdata('errors')['phone'] ?></span>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Password
                    </label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="new-password"
                        required
                        class="mt-1 appearance-none relative block w-full px-3 py-2 border <?= session()->getFlashdata('errors')['password'] ?? false ? 'border-red-500' : 'border-gray-300' ?> placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500 focus:z-10 sm:text-sm"
                        placeholder="Masukkan password"
                    />
                    <div class="mt-1 text-xs text-gray-600">
                        Password harus mengandung minimal 8 karakter dengan:
                        <ul class="list-disc list-inside mt-1">
                            <li>Huruf kecil (a-z)</li>
                            <li>Huruf besar (A-Z)</li>
                            <li>Angka (0-9)</li>
                            <li>Karakter khusus (!@#$%^&*)</li>
                        </ul>
                    </div>
                    <?php if (session()->getFlashdata('errors')['password'] ?? false): ?>
                        <span class="text-red-500 text-sm mt-1"><?= session()->getFlashdata('errors')['password'] ?></span>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700">
                        Konfirmasi Password
                    </label>
                    <input
                        id="confirm_password"
                        name="confirm_password"
                        type="password"
                        required
                        class="mt-1 appearance-none relative block w-full px-3 py-2 border <?= session()->getFlashdata('errors')['confirm_password'] ?? false ? 'border-red-500' : 'border-gray-300' ?> placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500 focus:z-10 sm:text-sm"
                        placeholder="Konfirmasi password"
                    />
                    <?php if (session()->getFlashdata('errors')['confirm_password'] ?? false): ?>
                        <span class="text-red-500 text-sm mt-1"><?= session()->getFlashdata('errors')['confirm_password'] ?></span>
                    <?php endif; ?>
                </div>

                <div>
                    <button
                        type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500"
                    >
                        Daftar
                    </button>
                </div>
            </form>

            <!-- Login Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Sudah punya akun? 
                    <a href="/login" class="font-medium text-teal-600 hover:text-teal-500">
                        Masuk di sini
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
// Real-time password validation sesuai requirement Level 4
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    
    // Create password strength indicator
    const passwordStrengthDiv = document.createElement('div');
    passwordStrengthDiv.className = 'mt-2';
    passwordStrengthDiv.innerHTML = `
        <div class="text-xs text-gray-600 mb-1">Kekuatan password:</div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div id="strength-bar" class="h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
        </div>
        <div id="strength-text" class="text-xs mt-1"></div>
        <div id="requirements" class="text-xs mt-2">
            <div id="req-length" class="text-red-500">✗ Minimal 8 karakter</div>
            <div id="req-lower" class="text-red-500">✗ Huruf kecil (a-z)</div>
            <div id="req-upper" class="text-red-500">✗ Huruf besar (A-Z)</div>
            <div id="req-number" class="text-red-500">✗ Angka (0-9)</div>
            <div id="req-special" class="text-red-500">✗ Karakter khusus (!@#$%^&*)</div>
        </div>
    `;
    
    passwordInput.parentNode.appendChild(passwordStrengthDiv);
    
    passwordInput.addEventListener('input', function() {
        validatePassword(this.value);
    });
    
    confirmPasswordInput.addEventListener('input', function() {
        validateConfirmPassword();
    });
    
    function validatePassword(password) {
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');
        
        let score = 0;
        let requirements = {
            length: password.length >= 8,
            lower: /[a-z]/.test(password),
            upper: /[A-Z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[!@#$%^&*]/.test(password)
        };
        
        // Update requirement indicators
        Object.keys(requirements).forEach(req => {
            const element = document.getElementById(`req-${req}`);
            if (requirements[req]) {
                element.className = 'text-green-500';
                element.innerHTML = element.innerHTML.replace('✗', '✓');
                score++;
            } else {
                element.className = 'text-red-500';
                element.innerHTML = element.innerHTML.replace('✓', '✗');
            }
        });
        
        // Update strength bar and text
        const percentage = (score / 5) * 100;
        strengthBar.style.width = percentage + '%';
        
        if (score <= 2) {
            strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-red-500';
            strengthText.className = 'text-xs mt-1 text-red-600';
            strengthText.textContent = 'Lemah';
        } else if (score <= 3) {
            strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-yellow-500';
            strengthText.className = 'text-xs mt-1 text-yellow-600';
            strengthText.textContent = 'Sedang';
        } else if (score <= 4) {
            strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-blue-500';
            strengthText.className = 'text-xs mt-1 text-blue-600';
            strengthText.textContent = 'Kuat';
        } else {
            strengthBar.className = 'h-2 rounded-full transition-all duration-300 bg-green-500';
            strengthText.className = 'text-xs mt-1 text-green-600';
            strengthText.textContent = 'Sangat Kuat';
        }
    }
    
    function validateConfirmPassword() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (confirmPassword && password !== confirmPassword) {
            confirmPasswordInput.classList.add('border-red-500');
            confirmPasswordInput.classList.remove('border-gray-300');
        } else {
            confirmPasswordInput.classList.remove('border-red-500');
            confirmPasswordInput.classList.add('border-gray-300');
        }
    }
});
</script>

<?= $this->endSection() ?>
