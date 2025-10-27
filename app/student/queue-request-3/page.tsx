'use client'

import { useState, useEffect } from 'react'
import { useRouter } from 'next/navigation'
import Link from 'next/link'
import QRCode from 'react-qr-code'

export default function QueueRequest3() {
  const router = useRouter()
  const [studentData, setStudentData] = useState<any>(null)
  const [additionalData, setAdditionalData] = useState<any>(null)
  const [queueNumber, setQueueNumber] = useState('')
  const [isGenerating, setIsGenerating] = useState(false)

  useEffect(() => {
    const student = sessionStorage.getItem('studentData')
    const additional = sessionStorage.getItem('additionalData')
    
    if (!student || !additional) {
      router.push('/student/queue-request')
      return
    }
    
    setStudentData(JSON.parse(student))
    setAdditionalData(JSON.parse(additional))
    generateQueueNumber()
  }, [router])

  const generateQueueNumber = async () => {
    setIsGenerating(true)
    
    try {
      // Generate queue number
      const prefix = 'Q'
      const randomNum = Math.floor(Math.random() * 999) + 1
      const queueNum = `${prefix}-${randomNum.toString().padStart(3, '0')}`
      setQueueNumber(queueNum)
      
      // Submit to API
      const response = await fetch('/api/queue/create', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          ...studentData,
          ...additionalData,
          queue_number: queueNum
        }),
      })
      
      if (!response.ok) {
        throw new Error('Failed to create queue request')
      }
      
      // Clear session data
      sessionStorage.removeItem('studentData')
      sessionStorage.removeItem('additionalData')
      
    } catch (error) {
      console.error('Error generating queue:', error)
      alert('Failed to generate queue number. Please try again.')
    } finally {
      setIsGenerating(false)
    }
  }

  if (!studentData || !additionalData) {
    return <div>Loading...</div>
  }

  const qrCodeData = `https://qms.uc.edu.ph/queue/${queueNumber}`

  return (
    <div className="min-h-screen flex flex-col bg-gradient-to-r from-white via-slate-200 to-sky-500">
      {/* Header */}
      <header className="bg-white border-b border-gray-300">
        <div className="flex items-center justify-between py-3 px-6 md:px-10 mx-20 md:mx-34 lg:mx-44">
          <Link href="/student" className="flex items-center hover:opacity-80 transition-opacity">
            <img alt="University of Cebu Student Affairs circular seal" className="h-12 w-12 rounded-full object-cover" src="/sao-nobg.png"/>
            <div className="ml-4 text-left">
              <h1 className="text-blue-900 font-bold text-xl -mb-1">SeQueueR</h1>
              <p className="text-gray-600 text-sm">UC Student Affairs</p>
            </div>
          </Link>
          <Link href="/personnel/signin" className="bg-yellow-400 text-black font-semibold text-sm px-5 py-2 rounded-md hover:bg-yellow-300 transition">
            Login
          </Link>
        </div>
      </header>

      {/* Main Content */}
      <main className="flex-grow flex items-start justify-center pt-20 pb-20">
        <div className="bg-white rounded-lg shadow-lg max-w-2xl w-full p-8">
          <div className="flex justify-center mb-6">
            <div className="bg-green-100 rounded-full p-4">
              <i className="fas fa-check text-green-400 text-xl"></i>
            </div>
          </div>
          <h2 className="text-blue-900 font-extrabold text-xl text-center mb-2">Queue Request Confirmed</h2>
          <p className="text-center text-slate-600 mb-6 text-sm">Your queue number has been generated successfully</p>
          
          <div className="flex items-center justify-between text-xs md:text-sm mb-4">
            <span className="font-semibold text-blue-900">Step 3 of 3</span>
            <span className="text-gray-500">Confirmation</span>
          </div>
          <div className="w-full h-1 rounded-full bg-slate-300 mb-6 relative">
            <div className="h-1 rounded-full bg-green-400 w-full"></div>
          </div>
          <hr className="border-slate-200 mb-6"/>
          
          {isGenerating ? (
            <div className="text-center py-12">
              <div className="loading-spinner mx-auto mb-4"></div>
              <p className="text-gray-600">Generating your queue number...</p>
            </div>
          ) : (
            <>
              {/* Queue Number Display */}
              <div className="text-center mb-8">
                <div className="bg-yellow-100 rounded-lg p-6 mb-4">
                  <h3 className="text-sm font-medium text-gray-600 mb-2">Your Queue Number</h3>
                  <div className="text-4xl font-bold text-yellow-600">{queueNumber}</div>
                </div>
                
                {additionalData.generate_qr === 'yes' && (
                  <div className="bg-white border border-gray-200 rounded-lg p-6 mb-4">
                    <h3 className="text-sm font-medium text-gray-600 mb-4">QR Code</h3>
                    <div className="flex justify-center">
                      <QRCode value={qrCodeData} size={200} />
                    </div>
                    <p className="text-xs text-gray-500 mt-2">Scan this QR code for easy tracking</p>
                  </div>
                )}
              </div>

              {/* Student Information Summary */}
              <div className="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 className="text-lg font-semibold text-gray-900 mb-4">Request Summary</h3>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <p className="text-sm text-gray-600">Student Name</p>
                    <p className="font-medium">{studentData.fullname}</p>
                  </div>
                  <div>
                    <p className="text-sm text-gray-600">Student ID</p>
                    <p className="font-medium">{studentData.studentid}</p>
                  </div>
                  <div>
                    <p className="text-sm text-gray-600">Course</p>
                    <p className="font-medium">{studentData.courseprogram}</p>
                  </div>
                  <div>
                    <p className="text-sm text-gray-600">Year Level</p>
                    <p className="font-medium">{studentData.yearlevel}</p>
                  </div>
                </div>
              </div>

              {/* Selected Services */}
              <div className="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 className="text-lg font-semibold text-gray-900 mb-4">Selected Services</h3>
                <div className="space-y-2">
                  {additionalData.selected_services.map((service: string, index: number) => (
                    <div key={index} className="flex items-center space-x-2">
                      <i className="fas fa-check text-green-500"></i>
                      <span className="text-sm">{service.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</span>
                    </div>
                  ))}
                </div>
              </div>

              {/* Instructions */}
              <div className="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <h3 className="text-lg font-semibold text-blue-900 mb-4">What's Next?</h3>
                <div className="space-y-2 text-sm text-blue-800">
                  <p>• Please wait for your queue number to be called</p>
                  <p>• You can track your position in the queue</p>
                  <p>• Keep your queue number safe</p>
                  <p>• Present your queue number when called</p>
                </div>
              </div>

              {/* Action Buttons */}
              <div className="flex justify-center space-x-4">
                <Link href="/student" className="flex items-center gap-2 border border-slate-300 rounded-md text-slate-700 text-sm hover:bg-slate-100 transition font-medium px-6 py-3">
                  <i className="fas fa-home text-sm"></i>
                  Back to Home
                </Link>
                <button 
                  onClick={() => window.print()} 
                  className="flex items-center gap-2 bg-blue-900 text-white rounded-md text-sm hover:bg-blue-800 transition font-medium px-6 py-3"
                >
                  <i className="fas fa-print text-sm"></i>
                  Print Queue Number
                </button>
              </div>
            </>
          )}
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
