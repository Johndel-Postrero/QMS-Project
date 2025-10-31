'use client'

import { useState, useEffect } from 'react'
import { useRouter } from 'next/navigation'
import Link from 'next/link'

const services = [
  { id: 'transcript', name: 'Transcript of Records', description: 'Official academic transcript' },
  { id: 'certificate', name: 'Certificate of Enrollment', description: 'Proof of current enrollment' },
  { id: 'good_moral', name: 'Good Moral Character', description: 'Character certificate' },
  { id: 'graduation', name: 'Graduation Requirements', description: 'Graduation clearance' },
  { id: 'scholarship', name: 'Scholarship Application', description: 'Financial aid application' },
  { id: 'clearance', name: 'Clearance', description: 'General clearance' },
  { id: 'id_replacement', name: 'ID Replacement', description: 'Student ID replacement' },
  { id: 'other', name: 'Other Services', description: 'Other student affairs services' }
]

export default function QueueRequest2() {
  const router = useRouter()
  const [studentData, setStudentData] = useState<any>(null)
  const [selectedServices, setSelectedServices] = useState<string[]>([])
  const [priorityGroup, setPriorityGroup] = useState('no')
  const [generateQr, setGenerateQr] = useState('no')

  useEffect(() => {
    const data = sessionStorage.getItem('studentData')
    if (!data) {
      router.push('/student/queue-request')
      return
    }
    setStudentData(JSON.parse(data))
  }, [router])

  const handleServiceToggle = (serviceId: string) => {
    setSelectedServices(prev => 
      prev.includes(serviceId) 
        ? prev.filter(id => id !== serviceId)
        : [...prev, serviceId]
    )
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    
    if (selectedServices.length === 0) {
      alert('Please select at least one service.')
      return
    }

    // Store additional data
    const additionalData = {
      selected_services: selectedServices,
      priority_group: priorityGroup,
      generate_qr: generateQr
    }
    
    sessionStorage.setItem('additionalData', JSON.stringify(additionalData))
    
    // Redirect to step 3
    router.push('/student/queue-request-3')
  }

  if (!studentData) {
    return <div>Loading...</div>
  }

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
        <form onSubmit={handleSubmit} className="bg-white rounded-lg shadow-lg max-w-2xl w-full p-8">
          <div className="flex justify-center mb-6">
            <div className="bg-yellow-100 rounded-full p-4">
              <i className="fas fa-clipboard-list text-yellow-400 text-xl"></i>
            </div>
          </div>
          <h2 className="text-blue-900 font-extrabold text-xl text-center mb-2">Select Services</h2>
          <p className="text-center text-slate-600 mb-6 text-sm">Choose the services you need from Student Affairs</p>
          
          <div className="flex items-center justify-between text-xs md:text-sm mb-4">
            <span className="font-semibold text-blue-900">Step 2 of 3</span>
            <span className="text-gray-500">Service Selection</span>
          </div>
          <div className="w-full h-1 rounded-full bg-slate-300 mb-6 relative">
            <div className="h-1 rounded-full bg-yellow-400 w-[66%]"></div>
          </div>
          <hr className="border-slate-200 mb-6"/>
          
          <h3 className="text-blue-900 font-semibold mb-4 text-sm">Available Services</h3>
          
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            {services.map((service) => (
              <div 
                key={service.id}
                className={`border rounded-lg p-4 cursor-pointer transition-all ${
                  selectedServices.includes(service.id)
                    ? 'border-yellow-400 bg-yellow-50'
                    : 'border-gray-200 hover:border-gray-300'
                }`}
                onClick={() => handleServiceToggle(service.id)}
              >
                <div className="flex items-start space-x-3">
                  <input
                    type="checkbox"
                    checked={selectedServices.includes(service.id)}
                    onChange={() => handleServiceToggle(service.id)}
                    className="mt-1"
                  />
                  <div className="flex-1">
                    <h4 className="font-medium text-gray-900">{service.name}</h4>
                    <p className="text-sm text-gray-600">{service.description}</p>
                  </div>
                </div>
              </div>
            ))}
          </div>

          <h3 className="text-blue-900 font-semibold mb-4 text-sm">Additional Options</h3>
          
          <div className="space-y-4 mb-8">
            <div className="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
              <div>
                <h4 className="font-medium text-gray-900">Priority Group</h4>
                <p className="text-sm text-gray-600">Are you part of a priority group?</p>
              </div>
              <select 
                className="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400"
                value={priorityGroup}
                onChange={(e) => setPriorityGroup(e.target.value)}
              >
                <option value="no">No</option>
                <option value="yes">Yes</option>
              </select>
            </div>
            
            <div className="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
              <div>
                <h4 className="font-medium text-gray-900">Generate QR Code</h4>
                <p className="text-sm text-gray-600">Receive QR code for easy tracking</p>
              </div>
              <select 
                className="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400"
                value={generateQr}
                onChange={(e) => setGenerateQr(e.target.value)}
              >
                <option value="no">No</option>
                <option value="yes">Yes</option>
              </select>
            </div>
          </div>
          
          <div className="flex justify-center" style={{gap: '80px'}}>
            <Link href="/student/queue-request" className="flex items-center gap-2 border border-slate-300 rounded-md text-slate-700 text-sm hover:bg-slate-100 transition font-medium" style={{padding: '16px 32px', width: '130px', height: '36px', justifyContent: 'center'}}>
              <i className="fas fa-arrow-left text-sm"></i>
              Back
            </Link>
            <button className="bg-blue-900 text-white rounded-md text-sm hover:bg-blue-800 transition flex items-center justify-center gap-2 font-medium" type="submit" style={{padding: '16px 32px', width: '130px', height: '36px'}}>
              Next
              <i className="fas fa-arrow-right text-sm"></i>
            </button>
          </div>
        </form>
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
