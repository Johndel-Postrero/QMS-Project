import { NextRequest, NextResponse } from 'next/server'
import { requireAuth } from '@/lib/auth'
import { getDashboardStats, getRecentActivity } from '@/lib/database'

export async function GET(request: NextRequest) {
  try {
    // Require authentication
    await requireAuth()

    // Get dashboard statistics
    const stats = await getDashboardStats()
    const recentActivity = await getRecentActivity(10)

    // Format recent activity for display
    const formattedActivity = recentActivity.map(activity => ({
      queueNumber: activity.queue_number,
      studentName: activity.full_name,
      studentId: activity.student_id,
      serviceType: activity.course_program,
      status: activity.status,
      date: new Date(activity.created_at).toLocaleDateString(),
      time: new Date(activity.created_at).toLocaleTimeString(),
      priority: 'normal', // You can add priority logic here
      additionalServices: 0 // You can add additional services logic here
    }))

    // Mock top services data (you can implement this based on your needs)
    const topServices = [
      { name: 'Transcript of Records', count: 15 },
      { name: 'Certificate of Enrollment', count: 12 },
      { name: 'Good Moral Character', count: 8 },
      { name: 'Clearance', count: 6 },
      { name: 'ID Replacement', count: 4 }
    ]

    const dashboardData = {
      summary: {
        totalQueuesToday: stats.total_queues_today || 0,
        currentlyServing: stats.currently_serving?.queue_number || '--',
        servingCounter: 'Counter 1',
        completedQueues: stats.completed_queues || 0,
        pendingQueues: stats.active_queues || 0
      },
      queueStatus: {
        waiting: stats.active_queues || 0,
        inService: 1, // Currently serving
        skipped: 0,
        completed: stats.completed_queues || 0,
        stalled: 0,
        cancelled: stats.cancelled_queues || 0
      },
      recentActivity: formattedActivity,
      topServices,
      systemStatus: {
        systemOnline: true,
        queueSystemActive: true,
        lastUpdated: new Date()
      }
    }

    return NextResponse.json(dashboardData)

  } catch (error) {
    console.error('Dashboard error:', error)
    return NextResponse.json(
      { error: 'Failed to load dashboard data' },
      { status: 500 }
    )
  }
}
