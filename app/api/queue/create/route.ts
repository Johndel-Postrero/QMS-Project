import { NextRequest, NextResponse } from 'next/server'
import { createQueueRequest, getNextQueueNumber } from '@/lib/database'

export async function POST(request: NextRequest) {
  try {
    const data = await request.json()
    
    const { 
      fullname, 
      studentid, 
      yearlevel, 
      courseprogram, 
      selected_services, 
      priority_group, 
      generate_qr 
    } = data

    if (!fullname || !studentid || !yearlevel || !courseprogram || !selected_services) {
      return NextResponse.json(
        { error: 'Missing required fields' },
        { status: 400 }
      )
    }

    // Generate queue number
    const nextNumber = await getNextQueueNumber()
    const queueNumber = `Q-${nextNumber.toString().padStart(3, '0')}`

    // Create queue request
    const queueRequest = await createQueueRequest({
      student_id: studentid,
      full_name: fullname,
      year_level: yearlevel,
      course_program: courseprogram,
      queue_number: queueNumber,
      status: 'active'
    })

    return NextResponse.json({
      success: true,
      queueRequest,
      message: 'Queue request created successfully'
    })

  } catch (error) {
    console.error('Create queue error:', error)
    return NextResponse.json(
      { error: 'Failed to create queue request' },
      { status: 500 }
    )
  }
}

export async function GET(request: NextRequest) {
  try {
    const { getQueueRequests } = await import('@/lib/database')
    const queues = await getQueueRequests()

    return NextResponse.json({
      success: true,
      queues
    })

  } catch (error) {
    console.error('Get queues error:', error)
    return NextResponse.json(
      { error: 'Failed to fetch queues' },
      { status: 500 }
    )
  }
}
