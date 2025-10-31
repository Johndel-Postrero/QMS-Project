'use client'

import { useState, useEffect } from 'react'
import Link from 'next/link'
import { logout } from '@/lib/auth'
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js'
import { Doughnut } from 'react-chartjs-2'

ChartJS.register(ArcElement, Tooltip, Legend)

interface User {
  id: number
  username: string
  email: string
  full_name: string
  role: 'admin' | 'staff'
}

interface DashboardData {
  summary: {
    totalQueuesToday: number
    currentlyServing: string
    servingCounter: string
    completedQueues: number
    pendingQueues: number
  }
  queueStatus: {
    waiting: number
    inService: number
    skipped: number
    completed: number
    stalled: number
    cancelled: number
  }
  recentActivity: Array<{
    queueNumber: string
    studentName: string
    studentId: string
    serviceType: string
    status: string
    date: string
    time: string
    priority?: string
    additionalServices?: number
  }>
  topServices: Array<{
    name: string
    count: number
  }>
  systemStatus: {
    systemOnline: boolean
    queueSystemActive: boolean
    lastUpdated: Date
  }
}

interface AdminDashboardProps {
  user: User
}

export default function AdminDashboard({ user }: AdminDashboardProps) {
  const [dashboardData, setDashboardData] = useState<DashboardData>({
    summary: {
      totalQueuesToday: 0,
      currentlyServing: '--',
      servingCounter: 'Counter 1',
      completedQueues: 0,
      pendingQueues: 0
    },
    queueStatus: {
      waiting: 0,
      inService: 0,
      skipped: 0,
      completed: 0,
      stalled: 0,
      cancelled: 0
    },
    recentActivity: [],
    topServices: [],
    systemStatus: {
      systemOnline: true,
      queueSystemActive: true,
      lastUpdated: new Date()
    }
  })

  const [isLoading, setIsLoading] = useState(true)

  useEffect(() => {
    loadDashboardData()
    
    // Auto-refresh every 30 seconds
    const interval = setInterval(loadDashboardData, 30000)
    return () => clearInterval(interval)
  }, [])

  const loadDashboardData = async () => {
    try {
      const response = await fetch('/api/admin/dashboard')
      if (response.ok) {
        const data = await response.json()
        setDashboardData(data)
      }
    } catch (error) {
      console.error('Failed to load dashboard data:', error)
    } finally {
      setIsLoading(false)
    }
  }

  const chartData = {
    labels: ['Waiting', 'In Service', 'Skipped', 'Completed', 'Stalled', 'Cancelled'],
    datasets: [{
      data: [
        dashboardData.queueStatus.waiting,
        dashboardData.queueStatus.inService,
        dashboardData.queueStatus.skipped,
        dashboardData.queueStatus.completed,
        dashboardData.queueStatus.stalled,
        dashboardData.queueStatus.cancelled
      ],
      backgroundColor: [
        '#3B82F6', // Blue
        '#6B7280', // Gray
        '#9CA3AF', // Light Gray
        '#10B981', // Green
        '#F59E0B', // Yellow
        '#EF4444'  // Red
      ],
      borderWidth: 0
    }]
  }

  const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: false
      }
    }
  }

  const handleLogout = () => {
    logout()
  }

  if (isLoading) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <div className="text-center">
          <div className="loading-spinner mx-auto mb-4"></div>
          <p className="text-gray-600">Loading dashboard...</p>
        </div>
      </div>
    )
  }

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <header className="bg-white border-b border-gray-300">
        <div className="flex items-center justify-between py-3 px-6 md:px-10 mx-4 md:mx-8 lg:mx-12">
          <Link href="/personnel/admin/dashboard" className="flex items-center hover:opacity-80 transition-opacity">
            <img alt="University of Cebu Student Affairs circular seal" className="h-12 w-12 rounded-full object-cover" src="/sao-nobg.png"/>
            <div className="ml-4 text-left">
              <h1 className="text-blue-900 font-bold text-xl -mb-1">SeQueueR</h1>
              <p className="text-gray-600 text-sm">UC Student Affairs - Admin</p>
            </div>
          </Link>
          
          <div className="flex items-center space-x-4">
            <div className="text-right">
              <p className="text-sm font-medium text-gray-900">{user.full_name}</p>
              <p className="text-xs text-gray-500">{user.role === 'admin' ? 'Administrator' : 'Staff'}</p>
            </div>
            <button 
              onClick={handleLogout}
              className="bg-red-600 text-white text-sm px-4 py-2 rounded-md hover:bg-red-700 transition"
            >
              Logout
            </button>
          </div>
        </div>
      </header>

      {/* Navigation */}
      <nav className="bg-white border-b border-gray-200">
        <div className="px-6 md:px-10 mx-4 md:mx-8 lg:mx-12">
          <div className="flex space-x-8">
            <Link href="/personnel/admin/dashboard" className="border-b-2 border-blue-600 py-4 text-blue-600 font-medium">
              Dashboard
            </Link>
            <Link href="/personnel/admin/queue" className="py-4 text-gray-600 hover:text-gray-900 transition">
              Queue Management
            </Link>
            <Link href="/personnel/admin/history" className="py-4 text-gray-600 hover:text-gray-900 transition">
              History
            </Link>
            <Link href="/personnel/admin/users" className="py-4 text-gray-600 hover:text-gray-900 transition">
              User Management
            </Link>
            <Link href="/personnel/admin/settings" className="py-4 text-gray-600 hover:text-gray-900 transition">
              Settings
            </Link>
          </div>
        </div>
      </nav>

      {/* Main Content */}
      <main className="min-h-screen">
        <div className="py-8 px-6 md:px-10 mx-4 md:mx-8 lg:mx-12">
          {/* Summary Cards Row */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {/* Total Queues Today */}
            <div className="stat-card bg-white rounded-lg shadow-sm border border-gray-200 p-6">
              <div className="flex items-center">
                <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                  <i className="fas fa-list text-blue-600 text-xl"></i>
                </div>
                <div className="ml-4">
                  <p className="text-3xl font-bold text-gray-900">{dashboardData.summary.totalQueuesToday}</p>
                  <p className="text-sm text-gray-600">Total Queues Today</p>
                </div>
              </div>
            </div>

            {/* Currently Serving */}
            <div className="stat-card bg-white rounded-lg shadow-sm border border-gray-200 p-6">
              <div className="flex items-center">
                <div className="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                  <i className="fas fa-user text-yellow-600 text-xl"></i>
                </div>
                <div className="ml-4">
                  <p className="text-3xl font-bold text-gray-900">{dashboardData.summary.currentlyServing}</p>
                  <p className="text-sm text-gray-600">Currently Serving</p>
                  <p className="text-xs text-gray-500">{dashboardData.summary.servingCounter}</p>
                </div>
              </div>
            </div>

            {/* Completed Queues */}
            <div className="stat-card bg-white rounded-lg shadow-sm border border-gray-200 p-6">
              <div className="flex items-center">
                <div className="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                  <i className="fas fa-check text-green-600 text-xl"></i>
                </div>
                <div className="ml-4">
                  <p className="text-3xl font-bold text-gray-900">{dashboardData.summary.completedQueues}</p>
                  <p className="text-sm text-gray-600">Completed Queues</p>
                </div>
              </div>
            </div>

            {/* Pending Queues */}
            <div className="stat-card bg-white rounded-lg shadow-sm border border-gray-200 p-6">
              <div className="flex items-center">
                <div className="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                  <i className="fas fa-clock text-orange-600 text-xl"></i>
                </div>
                <div className="ml-4">
                  <p className="text-3xl font-bold text-gray-900">{dashboardData.summary.pendingQueues}</p>
                  <p className="text-sm text-gray-600">Pending Queues</p>
                </div>
              </div>
            </div>
          </div>

          {/* Main Content Grid */}
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {/* Left Column - Queue Status Overview and Recent Activity */}
            <div className="lg:col-span-2 space-y-8">
              {/* Queue Status Overview */}
              <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 className="text-lg font-semibold text-gray-900 mb-6">Queue Status Overview</h3>
                <div className="flex flex-col lg:flex-row lg:items-center lg:space-x-8">
                  {/* Pie Chart */}
                  <div className="flex-1 mb-6 lg:mb-0">
                    <div className="h-64">
                      <Doughnut data={chartData} options={chartOptions} />
                    </div>
                  </div>
                  
                  {/* Legend */}
                  <div className="flex-1">
                    <div className="space-y-3">
                      {chartData.labels.map((label, index) => {
                        const value = chartData.datasets[0].data[index]
                        const total = chartData.datasets[0].data.reduce((sum, val) => sum + val, 0)
                        const percentage = total > 0 ? Math.round((value / total) * 100) : 0
                        return (
                          <div key={index} className="flex items-center justify-between">
                            <div className="flex items-center space-x-2">
                              <div 
                                className="w-3 h-3 rounded" 
                                style={{backgroundColor: chartData.datasets[0].backgroundColor[index]}}
                              ></div>
                              <span className="text-sm text-gray-700">{label}</span>
                            </div>
                            <span className="text-sm font-medium text-gray-900">{value} ({percentage}%)</span>
                          </div>
                        )
                      })}
                    </div>
                  </div>
                </div>
              </div>

              {/* Recent Activity */}
              <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div className="flex items-center justify-between mb-6">
                  <h3 className="text-lg font-semibold text-gray-900">Recent Activity</h3>
                  <Link href="/personnel/admin/history" className="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</Link>
                </div>
                
                {/* Activity Table */}
                <div className="overflow-x-auto">
                  <table className="min-w-full divide-y divide-gray-200">
                    <thead className="bg-gray-50">
                      <tr>
                        <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Queue Number</th>
                        <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                        <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Type</th>
                        <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                      </tr>
                    </thead>
                    <tbody className="bg-white divide-y divide-gray-200">
                      {dashboardData.recentActivity.length === 0 ? (
                        <tr>
                          <td colSpan={5} className="px-4 py-8 text-center text-gray-500">
                            No recent activity
                          </td>
                        </tr>
                      ) : (
                        dashboardData.recentActivity.map((activity, index) => (
                          <tr key={index} className="hover:bg-gray-50">
                            <td className="px-4 py-3 whitespace-nowrap">
                              <div className="flex items-center">
                                {activity.priority === 'priority' && <i className="fas fa-star text-yellow-500 mr-2"></i>}
                                <span className="text-sm font-medium text-blue-600">{activity.queueNumber}</span>
                              </div>
                            </td>
                            <td className="px-4 py-3 whitespace-nowrap">
                              <div>
                                <div className="text-sm font-medium text-gray-900">{activity.studentName}</div>
                                <div className="text-sm text-gray-500">{activity.studentId}</div>
                              </div>
                            </td>
                            <td className="px-4 py-3 whitespace-nowrap">
                              <div className="flex items-center">
                                <i className="fas fa-certificate text-yellow-500 mr-2"></i>
                                <span className="text-sm text-gray-900">{activity.serviceType}</span>
                                {activity.additionalServices && activity.additionalServices > 0 && (
                                  <span className="text-xs text-blue-600 ml-1">+{activity.additionalServices}</span>
                                )}
                              </div>
                            </td>
                            <td className="px-4 py-3 whitespace-nowrap">
                              <span className={`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${
                                activity.status === 'completed' ? 'bg-green-100 text-green-800' :
                                activity.status === 'cancelled' ? 'bg-red-100 text-red-800' :
                                'bg-yellow-100 text-yellow-800'
                              }`}>
                                {activity.status.charAt(0).toUpperCase() + activity.status.slice(1)}
                              </span>
                            </td>
                            <td className="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                              <div>
                                <div>{activity.date}</div>
                                <div className="text-gray-500">{activity.time}</div>
                              </div>
                            </td>
                          </tr>
                        ))
                      )}
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            {/* Right Column - Quick Actions, Top Services, System Status */}
            <div className="space-y-8">
              {/* Quick Actions */}
              <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 className="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
                <div className="space-y-3">
                  <Link href="/personnel/admin/queue" className="w-full flex items-center justify-center space-x-2 px-4 py-3 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                    <i className="fas fa-list"></i>
                    <span>Manage Queues</span>
                  </Link>
                  <Link href="/personnel/admin/users" className="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i className="fas fa-plus"></i>
                    <span>Add Account</span>
                  </Link>
                  <button className="w-full flex items-center justify-center space-x-2 px-4 py-3 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                    <i className="fas fa-download"></i>
                    <span>Generate Report</span>
                  </button>
                </div>
              </div>

              {/* Top Services Today */}
              <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 className="text-lg font-semibold text-gray-900 mb-6">Top Services Today</h3>
                <div className="space-y-4">
                  {dashboardData.topServices.length === 0 ? (
                    <p className="text-gray-500 text-sm">No services data available</p>
                  ) : (
                    dashboardData.topServices.map((service, index) => {
                      const maxCount = Math.max(...dashboardData.topServices.map(s => s.count))
                      return (
                        <div key={index} className="flex items-center justify-between">
                          <div className="flex-1">
                            <div className="flex items-center justify-between mb-1">
                              <span className="text-sm font-medium text-gray-900">{service.name}</span>
                              <span className="text-sm font-bold text-gray-900">{service.count}</span>
                            </div>
                            <div className="w-full bg-gray-200 rounded-full h-2">
                              <div 
                                className="service-bar bg-blue-600 h-2 rounded-full" 
                                style={{width: `${(service.count / maxCount) * 100}%`}}
                              ></div>
                            </div>
                          </div>
                        </div>
                      )
                    })
                  )}
                </div>
              </div>

              {/* System Status */}
              <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 className="text-lg font-semibold text-gray-900 mb-6">System Status</h3>
                <div className="space-y-4">
                  <div className="flex items-center space-x-3">
                    <div className={`w-3 h-3 rounded-full ${dashboardData.systemStatus.systemOnline ? 'bg-green-500' : 'bg-red-500'}`}></div>
                    <span className="text-sm font-medium text-gray-900">
                      {dashboardData.systemStatus.systemOnline ? 'System Online' : 'System Offline'}
                    </span>
                  </div>
                  <div className="flex items-center space-x-3">
                    <div className={`w-3 h-3 rounded-full ${dashboardData.systemStatus.queueSystemActive ? 'bg-green-500' : 'bg-red-500'}`}></div>
                    <span className="text-sm font-medium text-gray-900">
                      {dashboardData.systemStatus.queueSystemActive ? 'Queue System Active' : 'Queue System Inactive'}
                    </span>
                  </div>
                  <div className="text-xs text-gray-500 mt-4">
                    Last updated: {dashboardData.systemStatus.lastUpdated.toLocaleTimeString()}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>

      {/* Footer */}
      <footer className="bg-gray-800 text-white py-8">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center">
            <p className="text-sm text-gray-400">
              © 2024 University of Cebu Student Affairs Office. All rights reserved.
            </p>
          </div>
        </div>
      </footer>
    </div>
  )
}
