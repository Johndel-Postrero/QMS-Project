'use client'

import { useState, useEffect } from 'react'
import Link from 'next/link'
import { logout } from '@/lib/auth'

interface User {
  id: number
  username: string
  email: string
  full_name: string
  role: 'admin' | 'staff'
}

interface QueueItem {
  id: number
  student_id: string
  full_name: string
  year_level: string
  course_program: string
  queue_number: string
  status: string
  created_at: string
  waiting_time_minutes?: number
}

interface WorkingQueueProps {
  user: User
}

export default function WorkingQueue({ user }: WorkingQueueProps) {
  const [currentQueue, setCurrentQueue] = useState<QueueItem | null>(null)
  const [queueList, setQueueList] = useState<QueueItem[]>([])
  const [isLoading, setIsLoading] = useState(true)

  useEffect(() => {
    loadQueueData()
    
    // Auto-refresh every 10 seconds
    const interval = setInterval(loadQueueData, 10000)
    return () => clearInterval(interval)
  }, [])

  const loadQueueData = async () => {
    try {
      const response = await fetch('/api/queue/active')
      if (response.ok) {
        const data = await response.json()
        setQueueList(data.queues)
        
        // Set current serving queue (first in line)
        if (data.queues.length > 0) {
          setCurrentQueue(data.queues[0])
        }
      }
    } catch (error) {
      console.error('Failed to load queue data:', error)
    } finally {
      setIsLoading(false)
    }
  }

  const handleNextQueue = async () => {
    if (!currentQueue) return

    try {
      const response = await fetch('/api/queue/active', {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          id: currentQueue.id,
          status: 'completed'
        }),
      })

      if (response.ok) {
        // Reload queue data
        await loadQueueData()
      }
    } catch (error) {
      console.error('Failed to complete queue:', error)
    }
  }

  const handleSkipQueue = async () => {
    if (!currentQueue) return

    try {
      const response = await fetch('/api/queue/active', {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          id: currentQueue.id,
          status: 'cancelled'
        }),
      })

      if (response.ok) {
        // Reload queue data
        await loadQueueData()
      }
    } catch (error) {
      console.error('Failed to skip queue:', error)
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
          <p className="text-gray-600">Loading queue data...</p>
        </div>
      </div>
    )
  }

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <header className="bg-white border-b border-gray-300">
        <div className="flex items-center justify-between py-3 px-6 md:px-10 mx-4 md:mx-8 lg:mx-12">
          <Link href="/personnel/working/queue" className="flex items-center hover:opacity-80 transition-opacity">
            <img alt="University of Cebu Student Affairs circular seal" className="h-12 w-12 rounded-full object-cover" src="/sao-nobg.png"/>
            <div className="ml-4 text-left">
              <h1 className="text-blue-900 font-bold text-xl -mb-1">SeQueueR</h1>
              <p className="text-gray-600 text-sm">UC Student Affairs - Working</p>
            </div>
          </Link>
          
          <div className="flex items-center space-x-4">
            <div className="text-right">
              <p className="text-sm font-medium text-gray-900">{user.full_name}</p>
              <p className="text-xs text-gray-500">Staff</p>
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
            <Link href="/personnel/working/queue" className="border-b-2 border-blue-600 py-4 text-blue-600 font-medium">
              Queue Management
            </Link>
            <Link href="/personnel/working/history" className="py-4 text-gray-600 hover:text-gray-900 transition">
              History
            </Link>
          </div>
        </div>
      </nav>

      {/* Main Content */}
      <main className="bg-gray-100 min-h-screen">
        <div className="py-8 px-6 md:px-10 mx-4 md:mx-8 lg:mx-12">
          <div className="grid grid-cols-1 lg:grid-cols-10 gap-8">
            {/* Left Panel - Current Queue Details */}
            <div className="lg:col-span-7 space-y-6">
              {/* Currently Serving Card */}
              <div className="bg-white border-2 border-yellow-600 rounded-lg p-8 text-center shadow-sm">
                <div className="text-6xl font-bold text-yellow-600 mb-3">
                  {currentQueue ? currentQueue.queue_number : '--'}
                </div>
                <div className="flex items-center justify-center space-x-2">
                  <div className="w-3 h-3 bg-green-500 rounded-full"></div>
                  <span className="text-green-600 font-medium">Currently Serving</span>
                </div>
              </div>

              {/* Student Information & Queue Details Card */}
              {currentQueue && (
                <div className="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {/* Student Information */}
                    <div>
                      <h3 className="text-lg font-bold text-blue-800 mb-6 pb-2 border-b border-gray-200">Student Information</h3>
                      <div className="space-y-4">
                        <div>
                          <span className="text-sm text-gray-600 block mb-1">Full Name</span>
                          <p className="font-bold text-gray-800 text-base">{currentQueue.full_name}</p>
                        </div>
                        <div>
                          <span className="text-sm text-gray-600 block mb-1">Student ID</span>
                          <p className="font-bold text-gray-800 text-base">{currentQueue.student_id}</p>
                        </div>
                        <div>
                          <span className="text-sm text-gray-600 block mb-1">Course</span>
                          <p className="font-bold text-gray-800 text-base">{currentQueue.course_program}</p>
                        </div>
                        <div>
                          <span className="text-sm text-gray-600 block mb-1">Year Level</span>
                          <p className="font-bold text-gray-800 text-base">{currentQueue.year_level}</p>
                        </div>
                      </div>
                    </div>

                    {/* Queue Details */}
                    <div>
                      <h3 className="text-lg font-bold text-blue-800 mb-6 pb-2 border-b border-gray-200">Queue Details</h3>
                      <div className="space-y-4">
                        <div>
                          <span className="text-sm text-gray-600 block mb-2">Priority Type</span>
                          <div>
                            <span className="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-yellow-200 text-gray-800">
                              <i className="fas fa-star mr-2 text-black"></i>
                              Normal
                            </span>
                          </div>
                        </div>
                        <div>
                          <span className="text-sm text-gray-600 block mb-1">Time Requested</span>
                          <p className="font-bold text-gray-800 text-base">
                            {new Date(currentQueue.created_at).toLocaleTimeString()}
                          </p>
                        </div>
                        <div>
                          <span className="text-sm text-gray-600 block mb-1">Total Wait Time</span>
                          <p className="font-bold text-gray-800 text-base">
                            {currentQueue.waiting_time_minutes ? `${Math.floor(currentQueue.waiting_time_minutes)} minutes` : '--'}
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              )}

              {/* Requested Services */}
              <div className="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                <h3 className="text-lg font-bold text-blue-800 mb-6">Requested Services</h3>
                
                {currentQueue ? (
                  <div className="space-y-2">
                    <div className="flex items-center space-x-2">
                      <i className="fas fa-check text-green-500"></i>
                      <span className="text-sm">General Student Affairs Services</span>
                    </div>
                  </div>
                ) : (
                  <div className="text-center py-12 text-gray-500">
                    <i className="fas fa-clipboard-list text-4xl mb-4"></i>
                    <p className="text-lg font-medium">No services requested</p>
                    <p className="text-sm">Services will appear here when a student requests them</p>
                  </div>
                )}
              </div>
            </div>

            {/* Right Panel - Queue Management */}
            <div className="lg:col-span-3 space-y-6">
              {/* Queue Actions */}
              <div className="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                <h3 className="text-lg font-bold text-blue-800 mb-6">Queue Actions</h3>
                
                <div className="space-y-4">
                  <button 
                    onClick={handleNextQueue}
                    disabled={!currentQueue}
                    className="w-full bg-green-600 text-white font-medium py-3 px-4 rounded-lg hover:bg-green-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <i className="fas fa-check mr-2"></i>
                    Complete Current Queue
                  </button>
                  
                  <button 
                    onClick={handleSkipQueue}
                    disabled={!currentQueue}
                    className="w-full bg-orange-600 text-white font-medium py-3 px-4 rounded-lg hover:bg-orange-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <i className="fas fa-forward mr-2"></i>
                    Skip Current Queue
                  </button>
                  
                  <button 
                    onClick={loadQueueData}
                    className="w-full bg-blue-600 text-white font-medium py-3 px-4 rounded-lg hover:bg-blue-700 transition"
                  >
                    <i className="fas fa-sync-alt mr-2"></i>
                    Refresh Queue
                  </button>
                </div>
              </div>

              {/* Queue List */}
              <div className="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                <h3 className="text-lg font-bold text-blue-800 mb-6">Queue List</h3>
                
                <div className="space-y-3 max-h-96 overflow-y-auto">
                  {queueList.length === 0 ? (
                    <div className="text-center py-8 text-gray-500">
                      <i className="fas fa-list text-2xl mb-2"></i>
                      <p className="text-sm">No active queues</p>
                    </div>
                  ) : (
                    queueList.map((queue, index) => (
                      <div 
                        key={queue.id}
                        className={`p-3 rounded-lg border ${
                          index === 0 
                            ? 'border-yellow-400 bg-yellow-50' 
                            : 'border-gray-200 bg-gray-50'
                        }`}
                      >
                        <div className="flex items-center justify-between">
                          <div>
                            <p className="font-bold text-gray-900">{queue.queue_number}</p>
                            <p className="text-sm text-gray-600">{queue.full_name}</p>
                          </div>
                          <div className="text-right">
                            <p className="text-xs text-gray-500">
                              {new Date(queue.created_at).toLocaleTimeString()}
                            </p>
                            {index === 0 && (
                              <span className="text-xs bg-yellow-200 text-yellow-800 px-2 py-1 rounded-full">
                                Serving
                              </span>
                            )}
                          </div>
                        </div>
                      </div>
                    ))
                  )}
                </div>
              </div>

              {/* Queue Statistics */}
              <div className="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                <h3 className="text-lg font-bold text-blue-800 mb-6">Queue Statistics</h3>
                
                <div className="space-y-4">
                  <div className="flex justify-between">
                    <span className="text-sm text-gray-600">Total Active</span>
                    <span className="font-bold text-gray-900">{queueList.length}</span>
                  </div>
                  <div className="flex justify-between">
                    <span className="text-sm text-gray-600">Currently Serving</span>
                    <span className="font-bold text-gray-900">{currentQueue ? 1 : 0}</span>
                  </div>
                  <div className="flex justify-between">
                    <span className="text-sm text-gray-600">Waiting</span>
                    <span className="font-bold text-gray-900">{Math.max(0, queueList.length - 1)}</span>
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
