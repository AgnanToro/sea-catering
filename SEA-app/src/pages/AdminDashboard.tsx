import React, { useState, useEffect } from 'react';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  Title,
  Tooltip,
  Legend,
  ArcElement,
} from 'chart.js';
import { Line, Bar, Doughnut } from 'react-chartjs-2';
import { formatCurrency } from '../utils/helpers';

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  Title,
  Tooltip,
  Legend,
  ArcElement
);

interface AdminDashboardProps {
  setActiveTab: (tab: string) => void;
}

interface MetricData {
  totalSubscriptions: number;
  newSubscriptions: number;
  mrr: number;
  reactivations: number;
  growth: number;
}

interface Subscription {
  id: string;
  plan: string;
  status: 'active' | 'paused' | 'cancelled';
  startDate: string;
  nextBilling: string;
  price: number;
  mealsPerDay: number;
  deliveryDays: string[];
}

interface User {
  email: string;
  name: string;
  passwordHash: string;
  subscriptions: Subscription[];
}

interface ChartDataset {
  label?: string;
  data: number[];
  borderColor?: string | string[];
  backgroundColor?: string | string[];
  borderWidth?: number;
  tension?: number;
}

interface ChartData {
  labels: string[];
  datasets: ChartDataset[];
}

interface ActivityItem {
  activity: string;
  user: string;
  plan: string;
  timeAgo: string;
  icon: string;
  color: string;
}

const AdminDashboard: React.FC<AdminDashboardProps> = ({ setActiveTab }) => {
  const [dateFilter, setDateFilter] = useState({
    startDate: '2024-01-01',
    endDate: '2024-01-31'
  });

  const [metrics, setMetrics] = useState<MetricData>({
    totalSubscriptions: 0,
    newSubscriptions: 0,
    mrr: 0,
    reactivations: 0,
    growth: 0
  });

  const [chartData, setChartData] = useState<{
    growthChart: ChartData;
    revenueChart: ChartData;
    planDistribution: ChartData;
  }>({
    growthChart: { labels: [], datasets: [] },
    revenueChart: { labels: [], datasets: [] },
    planDistribution: { labels: [], datasets: [] }
  });

  const [recentActivities, setRecentActivities] = useState<ActivityItem[]>([]);

  // Calculate real metrics from actual user data
  useEffect(() => {
    // Import user data to calculate real metrics
    import('../utils/demoData').then(({ registeredUsers }) => {
      const totalSubscriptions = registeredUsers.reduce((total, user) => total + user.subscriptions.length, 0);
      const activeSubscriptions = registeredUsers.reduce((total, user) => 
        total + user.subscriptions.filter(sub => sub.status === 'active').length, 0
      );
      
      // Calculate MRR from active subscriptions (simplified calculation)
      const monthlyRevenue = registeredUsers.reduce((total, user) => {
        const userRevenue = user.subscriptions
          .filter(sub => sub.status === 'active')
          .reduce((subTotal, sub) => subTotal + sub.price, 0);
        return total + userRevenue;
      }, 0);

      // Calculate new subscriptions this month (demo: last 30 days)
      const thirtyDaysAgo = new Date();
      thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
      
      const newSubscriptionsThisMonth = registeredUsers.reduce((total, user) => {
        const newSubs = user.subscriptions.filter(sub => {
          const subDate = new Date(sub.startDate);
          return subDate >= thirtyDaysAgo;
        }).length;
        return total + newSubs;
      }, 0);

      // Calculate reactivations (paused -> active)
      const reactivations = registeredUsers.reduce((total, user) => {
        const pausedSubs = user.subscriptions.filter(sub => sub.status === 'paused').length;
        return total + pausedSubs;
      }, 0);

      // Calculate growth percentage
      const previousMonthActive = Math.max(1, activeSubscriptions - newSubscriptionsThisMonth);
      const growth = ((activeSubscriptions - previousMonthActive) / previousMonthActive) * 100;

      const realData = {
        totalSubscriptions,
        newSubscriptions: newSubscriptionsThisMonth,
        mrr: monthlyRevenue,
        reactivations,
        growth: Math.round(growth * 10) / 10 // Round to 1 decimal
      };
      
      setMetrics(realData);

      // Generate real chart data
      generateChartData(registeredUsers);
      generateRecentActivities(registeredUsers);
    });
  }, [dateFilter]);

  const generateChartData = (users: User[]) => {
    // Generate plan distribution from real data
    const planCounts = { 'Paket Diet': 0, 'Paket Protein': 0, 'Paket Royal': 0 };
    
    users.forEach(user => {
      user.subscriptions.forEach((sub: Subscription) => {
        if (sub.status === 'active') {
          planCounts[sub.plan as keyof typeof planCounts]++;
        }
      });
    });

    // Generate monthly data for growth chart (simulate historical data)
    const monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const currentSubscriptions = users.reduce((total, user) => total + user.subscriptions.filter((sub: Subscription) => sub.status === 'active').length, 0);
    
    // Simulate historical growth
    const totalSubsHistory = monthLabels.map((_, index) => {
      const growth = Math.floor(currentSubscriptions * (0.3 + (index * 0.1)));
      return Math.max(1, growth);
    });

    const newSubsHistory = monthLabels.map((_, index) => {
      return Math.floor(totalSubsHistory[index] * 0.2); // 20% are new each month
    });

    // Generate revenue history (in millions)
    const revenueHistory = monthLabels.map((_, index) => {
      return (totalSubsHistory[index] * 85000) / 1000000; // Average 85k per subscription
    });

    setChartData({
      growthChart: {
        labels: monthLabels,
        datasets: [
          {
            label: 'Total Subscriptions',
            data: totalSubsHistory,
            borderColor: 'rgb(20, 184, 166)',
            backgroundColor: 'rgba(20, 184, 166, 0.1)',
            tension: 0.3,
          },
          {
            label: 'New Subscriptions',
            data: newSubsHistory,
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.3,
          }
        ],
      },
      revenueChart: {
        labels: monthLabels.slice(-6), // Last 6 months
        datasets: [
          {
            label: 'MRR (Juta Rupiah)',
            data: revenueHistory.slice(-6),
            backgroundColor: 'rgba(20, 184, 166, 0.8)',
            borderColor: 'rgb(20, 184, 166)',
            borderWidth: 1,
          }
        ],
      },
      planDistribution: {
        labels: Object.keys(planCounts),
        datasets: [
          {
            data: Object.values(planCounts),
            backgroundColor: [
              'rgba(239, 68, 68, 0.8)',
              'rgba(20, 184, 166, 0.8)',
              'rgba(245, 158, 11, 0.8)',
            ],
            borderColor: [
              'rgb(239, 68, 68)',
              'rgb(20, 184, 166)',
              'rgb(245, 158, 11)',
            ],
            borderWidth: 2,
          }
        ],
      }
    });
  };

  const generateRecentActivities = (users: User[]) => {
    const activities: ActivityItem[] = [];
    
    users.forEach(user => {
      user.subscriptions.forEach((sub: Subscription) => {
        const startDate = new Date(sub.startDate);
        const daysSinceStart = Math.floor((new Date().getTime() - startDate.getTime()) / (1000 * 3600 * 24));
        
        let activity = null;
        let timeAgo = '';
        let icon = '';
        let color = '';

        if (sub.status === 'active' && daysSinceStart <= 7) {
          activity = 'New subscription';
          timeAgo = `${daysSinceStart} hari lalu`;
          icon = 'fas fa-user-plus';
          color = 'text-green-500';
        } else if (sub.status === 'paused') {
          activity = 'Subscription paused';
          timeAgo = `${Math.floor(Math.random() * 24) + 1} jam lalu`;
          icon = 'fas fa-pause';
          color = 'text-yellow-500';
        } else if (sub.status === 'cancelled') {
          activity = 'Subscription cancelled';
          timeAgo = `${Math.floor(Math.random() * 72) + 1} jam lalu`;
          icon = 'fas fa-times';
          color = 'text-red-500';
        }

        if (activity) {
          activities.push({
            activity,
            user: user.name,
            plan: sub.plan,
            timeAgo,
            icon,
            color
          });
        }
      });
    });

    // Sort by most recent and take first 4
    activities.sort(() => Math.random() - 0.5); // Random sort for demo
    setRecentActivities(activities.slice(0, 4));
  };

  // Growth Chart Data
  const growthChartData = chartData.growthChart;

  // Revenue Chart Data
  const revenueChartData = chartData.revenueChart;

  // Plan Distribution Chart Data
  const planDistributionData = chartData.planDistribution;

  const chartOptions = {
    responsive: true,
    plugins: {
      legend: {
        position: 'top' as const,
      },
    },
    scales: {
      y: {
        beginAtZero: true,
      },
    },
  };

  const handleDateFilterChange = (field: string, value: string) => {
    setDateFilter(prev => ({
      ...prev,
      [field]: value
    }));
  };

  return (
    <div className="min-h-screen bg-gray-50 py-8">
      <div className="container mx-auto px-4">
        {/* Header */}
        <div className="mb-8">
          <h1 className="text-3xl font-bold text-gray-900">Dashboard Admin</h1>
          <p className="text-gray-600 mt-2">Kelola dan pantau performa bisnis SEA Catering</p>
        </div>

        {/* Date Filter */}
        <div className="bg-white rounded-lg shadow-md p-6 mb-8">
          <h3 className="text-lg font-semibold text-gray-900 mb-4">Filter Waktu</h3>
          <div className="flex flex-col sm:flex-row gap-4">
            <div>
              <label htmlFor="startDate" className="block text-sm font-medium text-gray-700 mb-2">
                Tanggal Mulai
              </label>
              <input
                type="date"
                id="startDate"
                value={dateFilter.startDate}
                onChange={(e) => handleDateFilterChange('startDate', e.target.value)}
                className="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
              />
            </div>
            <div>
              <label htmlFor="endDate" className="block text-sm font-medium text-gray-700 mb-2">
                Tanggal Akhir
              </label>
              <input
                type="date"
                id="endDate"
                value={dateFilter.endDate}
                onChange={(e) => handleDateFilterChange('endDate', e.target.value)}
                className="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
              />
            </div>
            <div className="flex items-end">
              <button
                onClick={() => {
                  const today = new Date();
                  const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                  setDateFilter({
                    startDate: firstDay.toISOString().split('T')[0],
                    endDate: today.toISOString().split('T')[0]
                  });
                }}
                className="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition-colors"
              >
                Bulan Ini
              </button>
            </div>
          </div>
        </div>

        {/* Key Metrics */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div className="bg-white rounded-lg shadow-md p-6">
            <div className="flex items-center">
              <i className="fas fa-users text-blue-500 text-2xl mr-4"></i>
              <div>
                <p className="text-sm font-medium text-gray-600">Total Subscription</p>
                <p className="text-2xl font-bold text-gray-900">{metrics.totalSubscriptions}</p>
              </div>
            </div>
          </div>

          <div className="bg-white rounded-lg shadow-md p-6">
            <div className="flex items-center">
              <i className="fas fa-user-plus text-green-500 text-2xl mr-4"></i>
              <div>
                <p className="text-sm font-medium text-gray-600">Subscription Baru</p>
                <p className="text-2xl font-bold text-gray-900">{metrics.newSubscriptions}</p>
              </div>
            </div>
          </div>

          <div className="bg-white rounded-lg shadow-md p-6">
            <div className="flex items-center">
              <i className="fas fa-money-bill-wave text-teal-500 text-2xl mr-4"></i>
              <div>
                <p className="text-sm font-medium text-gray-600">MRR</p>
                <p className="text-2xl font-bold text-gray-900">{formatCurrency(metrics.mrr)}</p>
              </div>
            </div>
          </div>

          <div className="bg-white rounded-lg shadow-md p-6">
            <div className="flex items-center">
              <i className="fas fa-redo text-purple-500 text-2xl mr-4"></i>
              <div>
                <p className="text-sm font-medium text-gray-600">Reactivation</p>
                <p className="text-2xl font-bold text-gray-900">{metrics.reactivations}</p>
              </div>
            </div>
          </div>
        </div>

        {/* Growth Indicator */}
        <div className="bg-white rounded-lg shadow-md p-6 mb-8">
          <div className="flex items-center justify-between">
            <div>
              <h3 className="text-lg font-semibold text-gray-900">Pertumbuhan Bulanan</h3>
              <p className="text-gray-600">Perbandingan dengan bulan sebelumnya</p>
            </div>
            <div className={`flex items-center text-2xl font-bold ${
              metrics.growth >= 0 ? 'text-green-600' : 'text-red-600'
            }`}>
              <i className={`fas ${metrics.growth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down'} mr-2`}></i>
              {Math.abs(metrics.growth).toFixed(1)}%
            </div>
          </div>
        </div>

        {/* Charts */}
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
          {/* Growth Chart */}
          <div className="bg-white rounded-lg shadow-md p-6">
            <h3 className="text-lg font-semibold text-gray-900 mb-4">Grafik Pertumbuhan</h3>
            <Line data={growthChartData} options={chartOptions} />
          </div>

          {/* Revenue Chart */}
          <div className="bg-white rounded-lg shadow-md p-6">
            <h3 className="text-lg font-semibold text-gray-900 mb-4">Revenue Bulanan (MRR)</h3>
            <Bar data={revenueChartData} options={chartOptions} />
          </div>
        </div>

        {/* Plan Distribution */}
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
          <div className="bg-white rounded-lg shadow-md p-6">
            <h3 className="text-lg font-semibold text-gray-900 mb-4">Distribusi Paket</h3>
            <Doughnut data={planDistributionData} />
          </div>

          {/* Recent Activities */}
          <div className="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
            <h3 className="text-lg font-semibold text-gray-900 mb-4">Aktivitas Terbaru</h3>
            <div className="space-y-4">
              {recentActivities.length > 0 ? (
                recentActivities.map((activity, index) => (
                  <div key={index} className="flex items-center justify-between py-3 border-b border-gray-200">
                    <div className="flex items-center">
                      <i className={`${activity.icon} ${activity.color} mr-3`}></i>
                      <div>
                        <p className="font-medium text-gray-900">{activity.activity}</p>
                        <p className="text-sm text-gray-600">{activity.user} - {activity.plan}</p>
                      </div>
                    </div>
                    <span className="text-sm text-gray-500">{activity.timeAgo}</span>
                  </div>
                ))
              ) : (
                <div className="text-center py-8">
                  <i className="fas fa-inbox text-gray-400 text-3xl mb-4"></i>
                  <p className="text-gray-500">Belum ada aktivitas terbaru</p>
                </div>
              )}
            </div>
          </div>
        </div>

        {/* Admin Actions */}
        <div className="bg-white rounded-lg shadow-md p-6">
          <h3 className="text-lg font-semibold text-gray-900 mb-4">Aksi Admin</h3>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <button
              onClick={() => setActiveTab('menu')}
              className="bg-teal-600 hover:bg-teal-700 text-white px-4 py-3 rounded-lg font-medium transition-colors"
            >
              <i className="fas fa-utensils mr-2"></i>
              Kelola Menu
            </button>
            
            <button
              onClick={() => alert('Fitur export dalam pengembangan')}
              className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium transition-colors"
            >
              <i className="fas fa-download mr-2"></i>
              Export Data
            </button>
            
            <button
              onClick={() => alert('Fitur laporan dalam pengembangan')}
              className="bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg font-medium transition-colors"
            >
              <i className="fas fa-chart-bar mr-2"></i>
              Laporan
            </button>
            
            <button
              onClick={() => setActiveTab('contact')}
              className="bg-gray-600 hover:bg-gray-700 text-white px-4 py-3 rounded-lg font-medium transition-colors"
            >
              <i className="fas fa-cog mr-2"></i>
              Pengaturan
            </button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default AdminDashboard;
