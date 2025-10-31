import { NextRequest, NextResponse } from 'next/server'
import { requireStaff } from '@/lib/auth'
import { getActiveQueues, updateQueueRequestStatus } from '@/lib/database'

export async function GET(request: NextRequest) {
  try {
    // Require staff authentication
    await requireStaff()

    const activeQueues = await getActiveQueues()

    return NextResponse.json({
      success: true,
      queues: activeQueues
    })

  } catch (error) {
    console.error('Get active queues error:', error)
    return NextResponse.json(
      { error: 'Failed to fetch active queues' },
      { status: 500 }
    )
  }
}

export async function PUT(request: NextRequest) {
  try {
    // Require staff authentication
    await requireStaff()

    const { id, status } = await request.json()

    if (!id || !status) {
      return NextResponse.json(
        { error: 'Queue ID and status are required' },
        { status: 400 }
      )
    }

    const updatedQueue = await updateQueueRequestStatus(id, status)

    return NextResponse.json({
      success: true,
      queue: updatedQueue,
      message: 'Queue status updated successfully'
    })

  } catch (error) {
    console.error('Update queue status error:', error)
    return NextResponse.json(
      { error: 'Failed to update queue status' },
      { status: 500 }
    )
  }
}
