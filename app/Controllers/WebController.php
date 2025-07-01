<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SubscriptionModel;
use App\Models\ContactMessageModel;
use App\Models\UserModel;

class WebController extends BaseController
{
    protected $session;
    protected $userModel;
    protected $subscriptionModel;
    protected $contactModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->userModel = new UserModel();
        $this->subscriptionModel = new SubscriptionModel();
        $this->contactModel = new ContactMessageModel();
        
        helper(['form', 'url']);
    }

    public function index()
    {
        $data = [
            'title' => 'SEA Catering - Healthy Meals Delivery',
            'page' => 'home'
        ];
        
        return view('pages/home', $data);
    }

    public function menu()
    {
        $data = [
            'title' => 'Menu - SEA Catering',
            'page' => 'menu'
        ];
        
        return view('pages/menu', $data);
    }

    public function subscription()
    {
        $data = [
            'title' => 'Berlangganan - SEA Catering',
            'page' => 'subscription'
        ];
        
        return view('pages/subscription', $data);
    }

    public function contact()
    {
        $data = [
            'title' => 'Hubungi Kami - SEA Catering',
            'page' => 'contact'
        ];
        
        return view('pages/contact', $data);
    }

    public function login()
    {
        // Redirect jika sudah login
        if ($this->session->get('isLoggedIn')) {
            $userRole = $this->session->get('userRole');
            return redirect()->to($userRole === 'admin' ? '/dashboard/admin' : '/dashboard');
        }

        $data = [
            'title' => 'Login - SEA Catering',
            'page' => 'login'
        ];
        
        return view('pages/login', $data);
    }

    public function register()
    {
        // Redirect jika sudah login
        if ($this->session->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Daftar - SEA Catering',
            'page' => 'register'
        ];
        
        return view('pages/register', $data);
    }

    public function dashboard()
    {
        // Check authentication
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userRole = $this->session->get('userRole');
        $userId = $this->session->get('user_id');  // Use consistent key
        
        if ($userRole === 'admin') {
            return $this->adminDashboard();
        } else {
            return $this->userDashboard();
        }
    }

    public function adminDashboard()
    {
        // Check admin access
        if (!$this->session->get('isLoggedIn') || !$this->session->get('is_admin')) {
            return redirect()->to('/login');
        }

        // Get dashboard statistics
        $totalUsers = $this->userModel->countAll();
        $totalSubscriptions = $this->subscriptionModel->countAll();
        $activeSubscriptions = $this->subscriptionModel->where('status', 'active')->countAllResults();
        $totalContacts = $this->contactModel->countAll();
        $unreadContacts = $this->contactModel->where('status', 'unread')->countAllResults();
        
        // Calculate MRR (Monthly Recurring Revenue)
        $mrr = $this->subscriptionModel
            ->select('SUM(price) as total')
            ->where('status', 'active')
            ->first()['total'] ?? 0;

        // Get subscription growth data (last 6 months)
        $growthData = [];
        $revenueData = [];
        $labels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = date('Y-m-01', strtotime("-$i months"));
            $monthEnd = date('Y-m-t', strtotime("-$i months"));
            $monthName = date('M', strtotime("-$i months")); // Format pendek: Jan, Feb, Mar
            
            // Count new subscriptions in this month
            $newSubscriptions = $this->subscriptionModel
                ->where('created_at >=', $date)
                ->where('created_at <=', $monthEnd . ' 23:59:59')
                ->countAllResults();
            
            // Calculate revenue for this month (active subscriptions created before or during this month)
            $monthlyRevenue = $this->subscriptionModel
                ->select('SUM(price) as total')
                ->where('status', 'active')
                ->where('created_at <=', $monthEnd . ' 23:59:59')
                ->first()['total'] ?? 0;
            
            $labels[] = $monthName;
            $growthData[] = $newSubscriptions;
            $revenueData[] = round($monthlyRevenue / 1000000, 2); // Convert to millions
        }

        // Get current month statistics for comparison
        $currentMonth = date('Y-m-01');
        $lastMonth = date('Y-m-01', strtotime('-1 month'));
        
        $currentMonthSubscriptions = $this->subscriptionModel
            ->where('created_at >=', $currentMonth)
            ->countAllResults();
            
        $lastMonthSubscriptions = $this->subscriptionModel
            ->where('created_at >=', $lastMonth)
            ->where('created_at <', $currentMonth)
            ->countAllResults();
            
        $subscriptionGrowthPercent = $lastMonthSubscriptions > 0 
            ? round((($currentMonthSubscriptions - $lastMonthSubscriptions) / $lastMonthSubscriptions) * 100, 1)
            : 0;

        // Get recent subscriptions
        $recentSubscriptions = $this->subscriptionModel
            ->select('subscriptions.*, users.name as user_name, users.email as user_email')
            ->join('users', 'users.id = subscriptions.user_id')
            ->orderBy('subscriptions.created_at', 'DESC')
            ->limit(10)
            ->findAll();

        $data = [
            'title' => 'Admin Dashboard - SEA Catering',
            'page' => 'admin-dashboard',
            'stats' => [
                'totalUsers' => $totalUsers,
                'totalSubscriptions' => $totalSubscriptions,
                'activeSubscriptions' => $activeSubscriptions,
                'totalContacts' => $totalContacts,
                'unreadContacts' => $unreadContacts,
                'mrr' => $mrr,
                'subscriptionGrowthPercent' => $subscriptionGrowthPercent,
                'currentMonthSubscriptions' => $currentMonthSubscriptions,
                'lastMonthSubscriptions' => $lastMonthSubscriptions
            ],
            'chartData' => [
                'labels' => $labels,
                'growthData' => $growthData,
                'revenueData' => $revenueData
            ],
            'recentSubscriptions' => $recentSubscriptions
        ];
        
        return view('pages/admin_dashboard', $data);
    }

    public function userDashboard()
    {
        // Check user access
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = $this->session->get('user_id');  // Use consistent key
        
        // Get user subscriptions
        $subscriptions = $this->subscriptionModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Dashboard - SEA Catering',
            'page' => 'user-dashboard',
            'subscriptions' => $subscriptions
        ];
        
        return view('pages/user_dashboard', $data);
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/')->with('message', 'Logout berhasil!');
    }

    public function processRegister()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back()->with('error', 'Method tidak diizinkan');
        }

        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $phone = $this->request->getPost('phone');
        $password = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Validasi input
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'name' => 'required|min_length[3]|max_length[255]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'phone' => 'required|regex_match[/^(\+62|62|0)8[1-9][0-9]{6,9}$/]',
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]'
        ], [
            'name' => [
                'required' => 'Nama harus diisi',
                'min_length' => 'Nama minimal 3 karakter'
            ],
            'email' => [
                'required' => 'Email harus diisi',
                'valid_email' => 'Format email tidak valid',
                'is_unique' => 'Email sudah terdaftar'
            ],
            'phone' => [
                'required' => 'Nomor HP harus diisi',
                'regex_match' => 'Format nomor HP tidak valid (contoh: 08xxxxxxxxxx)'
            ],
            'password' => [
                'required' => 'Password harus diisi',
                'min_length' => 'Password minimal 8 karakter'
            ],
            'confirm_password' => [
                'required' => 'Konfirmasi password harus diisi',
                'matches' => 'Password tidak cocok'
            ]
        ]);

        if (!$validation->run([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'confirm_password' => $confirmPassword
        ])) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Enhanced password validation sesuai requirement Level 4
        if (strlen($password) < 8) {
            return redirect()->back()->withInput()->with('error', 'Password minimal 8 karakter!');
        }

        // Password harus mengandung: uppercase, lowercase, number, dan special character
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])/', $password)) {
            return redirect()->back()->withInput()->with('error', 'Password harus mengandung minimal 8 karakter dengan huruf kecil, huruf besar, angka, dan karakter khusus (!@#$%^&*)');
        }

        try {
            // Create user
            $result = $this->userModel->createUser([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => $password,
                'is_admin' => false
            ]);

            if (!$result['success']) {
                return redirect()->back()->withInput()->with('error', implode(', ', $result['errors']));
            }

            return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');

        } catch (\Exception $e) {
            log_message('error', 'Registration error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.');
        }
    }

    public function processLogin()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back()->with('error', 'Method tidak diizinkan');
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Validasi input
        if (empty($email) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Email dan password harus diisi!');
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->withInput()->with('error', 'Format email tidak valid!');
        }

        try {
            log_message('info', 'Login attempt for email: ' . $email);
            
            // Find user by email
            $user = $this->userModel->findByEmail($email);
            
            if (!$user) {
                log_message('warning', 'Login failed: User not found for email: ' . $email);
                return redirect()->back()->withInput()->with('error', 'Email atau password salah!');
            }

            log_message('info', 'User found: ' . $user['name'] . ', is_admin: ' . ($user['is_admin'] ? 'true' : 'false'));

            // Verify password
            if (!$this->userModel->verifyPassword($password, $user['password'])) {
                log_message('warning', 'Login failed: Invalid password for email: ' . $email);
                return redirect()->back()->withInput()->with('error', 'Email atau password salah!');
            }

            log_message('info', 'Password verified successfully for user: ' . $user['email']);

            // Set session data
            $sessionData = [
                'isLoggedIn' => true,
                'user_id' => $user['id'],      // Changed from userId to user_id
                'name' => $user['name'],       // Changed from userName to name
                'email' => $user['email'],     // Changed from userEmail to email
                'is_admin' => $user['is_admin'] ? true : false,  // Added is_admin boolean
                'userRole' => $user['is_admin'] ? 'admin' : 'user'  // Keep for backward compatibility
            ];

            $this->session->set($sessionData);
            
            log_message('info', 'Session set successfully for user: ' . $user['email']);

            // Redirect based on user role
            if ($user['is_admin']) {
                log_message('info', 'Redirecting admin user to admin dashboard');
                return redirect()->to('/admin/dashboard')->with('success', 'Selamat datang, Admin!');
            } else {
                log_message('info', 'Redirecting regular user to user dashboard');
                return redirect()->to('/dashboard')->with('success', 'Login berhasil!');
            }

        } catch (\Exception $e) {
            log_message('error', 'Login error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat login. Silakan coba lagi.');
        }
    }

    public function createSubscription()
    {
        try {
            // Check if user is logged in
            if (!$this->session->get('isLoggedIn')) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Anda harus login terlebih dahulu'
                ])->setStatusCode(401);
            }

            // Get user data from session
            $userId = $this->session->get('user_id');
            
            // Get input data
            $input = $this->request->getJSON(true);
            
            // Debug: log input data
            log_message('info', 'Subscription input data: ' . json_encode($input));
            
            // Validate required fields
            $validation = \Config\Services::validation();
            $validation->setRules([
                'plan' => 'required|in_list[diet,protein,royal]',
                'price' => 'required|numeric'
            ]);

            // Check if meal_types and delivery_days are arrays and not empty
            if (empty($input['meal_types']) || !is_array($input['meal_types'])) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Pilih minimal satu jenis makanan'
                ])->setStatusCode(422);
            }

            if (empty($input['delivery_days']) || !is_array($input['delivery_days'])) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Pilih minimal satu hari pengiriman'
                ])->setStatusCode(422);
            }

            if (!$validation->run($input)) {
                $errors = $validation->getErrors();
                log_message('error', 'Validation errors: ' . json_encode($errors));
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data tidak valid: ' . implode(', ', $errors),
                    'errors' => $errors
                ])->setStatusCode(422);
            }

            // Prepare data for database
            $mealTypesArray = $input['meal_types'];
            $deliveryDaysArray = $input['delivery_days'];
            
            log_message('info', 'Meal types: ' . json_encode($mealTypesArray));
            log_message('info', 'Delivery days: ' . json_encode($deliveryDaysArray));
            
            $data = [
                'user_id' => $userId,
                'plan' => $input['plan'],
                'delivery_days' => json_encode($deliveryDaysArray), // Store as JSON
                'allergies' => $input['allergies'] ?? '',
                'meals_per_day' => count($mealTypesArray),
                'price' => $input['price'],
                'start_date' => date('Y-m-d', strtotime('+1 day')), // Start tomorrow
                'end_date' => date('Y-m-d', strtotime('+1 month')), // End in 1 month
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            log_message('info', 'Subscription data to insert: ' . json_encode($data));

            // Insert to database
            $subscriptionId = $this->subscriptionModel->insert($data);
            
            if (!$subscriptionId) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal menyimpan subscription ke database'
                ])->setStatusCode(500);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Subscription berhasil dibuat!',
                'data' => [
                    'subscription_id' => $subscriptionId,
                    'plan' => $input['plan'],
                    'price' => $input['price']
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Create subscription error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat membuat subscription: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function exportData()
    {
        // Check if user is admin
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied');
        }

        try {
            // Get all data
            $subscriptions = $this->subscriptionModel
                ->select('subscriptions.*, users.name as user_name, users.email as user_email')
                ->join('users', 'users.id = subscriptions.user_id')
                ->findAll();

            $contacts = $this->contactModel->findAll();
            $users = $this->userModel->findAll();

            // Create CSV content
            $csvContent = "Data Export - SEA Catering\n";
            $csvContent .= "Generated on: " . date('Y-m-d H:i:s') . "\n\n";

            // Subscriptions section
            $csvContent .= "SUBSCRIPTIONS\n";
            $csvContent .= "ID,User Name,Email,Plan,Status,Price,Start Date,End Date,Created\n";
            
            foreach ($subscriptions as $sub) {
                $csvContent .= sprintf(
                    "%d,%s,%s,%s,%s,%.2f,%s,%s,%s\n",
                    $sub['id'],
                    '"' . str_replace('"', '""', $sub['user_name']) . '"',
                    '"' . str_replace('"', '""', $sub['user_email']) . '"',
                    $sub['plan'],
                    $sub['status'],
                    $sub['price'],
                    $sub['start_date'],
                    $sub['end_date'],
                    $sub['created_at']
                );
            }

            // Users section
            $csvContent .= "\nUSERS\n";
            $csvContent .= "ID,Name,Email,Role,Created\n";
            
            foreach ($users as $user) {
                $csvContent .= sprintf(
                    "%d,%s,%s,%s,%s\n",
                    $user['id'],
                    '"' . str_replace('"', '""', $user['name']) . '"',
                    '"' . str_replace('"', '""', $user['email']) . '"',
                    $user['role'],
                    $user['created_at']
                );
            }

            // Contacts section
            $csvContent .= "\nCONTACT MESSAGES\n";
            $csvContent .= "ID,Name,Email,Message,Created\n";
            
            foreach ($contacts as $contact) {
                $csvContent .= sprintf(
                    "%d,%s,%s,%s,%s\n",
                    $contact['id'],
                    '"' . str_replace('"', '""', $contact['name']) . '"',
                    '"' . str_replace('"', '""', $contact['email']) . '"',
                    '"' . str_replace('"', '""', $contact['message']) . '"',
                    $contact['created_at']
                );
            }

            // Set headers for file download
            $filename = 'sea_catering_data_' . date('Y-m-d_H-i-s') . '.csv';
            
            return $this->response
                ->setHeader('Content-Type', 'text/csv')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->setHeader('Cache-Control', 'no-cache, must-revalidate')
                ->setBody($csvContent);

        } catch (\Exception $e) {
            log_message('error', 'Export data error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }

    public function generateReport()
    {
        // Check if user is admin
        if (!$this->session->get('isLoggedIn') || !$this->session->get('is_admin')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied'])->setStatusCode(403);
        }

        try {
            $input = $this->request->getJSON(true);
            $startDate = $input['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
            $endDate = $input['end_date'] ?? date('Y-m-d');

            // Get data for the specified period
            $subscriptions = $this->subscriptionModel
                ->select('subscriptions.*, users.name as user_name, users.email as user_email')
                ->join('users', 'users.id = subscriptions.user_id')
                ->where('subscriptions.created_at >=', $startDate . ' 00:00:00')
                ->where('subscriptions.created_at <=', $endDate . ' 23:59:59')
                ->findAll();

            // Calculate statistics
            $totalSubscriptions = count($subscriptions);
            $totalRevenue = array_sum(array_column($subscriptions, 'price'));
            $activeSubscriptions = array_filter($subscriptions, function($sub) {
                return $sub['status'] === 'active';
            });
            
            $planCounts = [];
            foreach ($subscriptions as $sub) {
                $plan = $sub['plan'];
                $planCounts[$plan] = ($planCounts[$plan] ?? 0) + 1;
            }

            // Generate report content
            $reportContent = "SEA CATERING - BUSINESS REPORT\n";
            $reportContent .= "Period: {$startDate} to {$endDate}\n";
            $reportContent .= "Generated: " . date('Y-m-d H:i:s') . "\n";
            $reportContent .= str_repeat("=", 50) . "\n\n";

            $reportContent .= "SUMMARY STATISTICS\n";
            $reportContent .= "Total Subscriptions: {$totalSubscriptions}\n";
            $reportContent .= "Active Subscriptions: " . count($activeSubscriptions) . "\n";
            $reportContent .= "Total Revenue: Rp " . number_format($totalRevenue, 0, ',', '.') . "\n";
            $reportContent .= "Average Order Value: Rp " . number_format($totalRevenue / max($totalSubscriptions, 1), 0, ',', '.') . "\n\n";

            $reportContent .= "PLAN BREAKDOWN\n";
            foreach ($planCounts as $plan => $count) {
                $percentage = round(($count / $totalSubscriptions) * 100, 1);
                $reportContent .= "- {$plan}: {$count} subscriptions ({$percentage}%)\n";
            }

            $reportContent .= "\nDETAILED SUBSCRIPTIONS\n";
            $reportContent .= str_repeat("-", 80) . "\n";
            $reportContent .= sprintf("%-5s %-20s %-25s %-10s %-10s %-15s\n", "ID", "User", "Email", "Plan", "Status", "Price");
            $reportContent .= str_repeat("-", 80) . "\n";
            
            foreach ($subscriptions as $sub) {
                $reportContent .= sprintf(
                    "%-5d %-20s %-25s %-10s %-10s Rp %s\n",
                    $sub['id'],
                    substr($sub['user_name'], 0, 19),
                    substr($sub['user_email'], 0, 24),
                    $sub['plan'],
                    $sub['status'],
                    number_format($sub['price'], 0, ',', '.')
                );
            }

            // Save report to writable directory
            $filename = 'report_' . date('Y-m-d_H-i-s') . '.txt';
            $filepath = WRITEPATH . 'uploads/' . $filename;
            
            if (!is_dir(WRITEPATH . 'uploads/')) {
                mkdir(WRITEPATH . 'uploads/', 0755, true);
            }
            
            file_put_contents($filepath, $reportContent);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Report generated successfully',
                'filename' => $filename,
                'download_url' => base_url('download/report/' . $filename)
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Generate report error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal generate report: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function downloadReport($filename)
    {
        // Check if user is admin
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied');
        }

        $filepath = WRITEPATH . 'uploads/' . $filename;
        
        if (!file_exists($filepath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found');
        }

        return $this->response->download($filepath, null);
    }

    public function debugAdmin()
    {
        $sessionData = [
            'isLoggedIn' => $this->session->get('isLoggedIn'),
            'user_id' => $this->session->get('user_id'),
            'name' => $this->session->get('name'),
            'email' => $this->session->get('email'),
            'is_admin' => $this->session->get('is_admin'),
            'userRole' => $this->session->get('userRole')
        ];

        return $this->response->setJSON([
            'session_data' => $sessionData,
            'session_id' => session_id(),
            'session_status' => session_status(),
            'all_session_data' => $_SESSION ?? []
        ]);
    }

    public function testAdminLogin()
    {
        // Create admin session for testing (ONLY FOR DEVELOPMENT)
        $sessionData = [
            'isLoggedIn' => true,
            'user_id' => 1,
            'name' => 'Admin SEA Apps',
            'email' => 'admin@seaapps.com',
            'is_admin' => true,
            'userRole' => 'admin'
        ];

        $this->session->set($sessionData);
        
        // Redirect to admin dashboard
        return redirect()->to('/admin/dashboard')->with('success', 'Admin session created for testing');
    }
}
