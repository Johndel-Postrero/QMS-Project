'use client'

import { useState } from 'react'
import { useRouter } from 'next/navigation'
import Link from 'next/link'

const courses = [
  'BS Computer Science',
  'BS Information Technology', 
  'BS Business Administration',
  'BS Accountancy',
  'BS Psychology',
  'BS Education',
  'BS Nursing',
  'BS Engineering',
  'BS Architecture',
  'BS Tourism',
  'BS Criminology',
  'BS Social Work',
  'BS Hotel and Restaurant Management',
  'BS Tourism Management'
]

export default function QueueRequest() {
  const router = useRouter()
  const [formData, setFormData] = useState({
    fullname: '',
    studentid: '',
    yearlevel: '',
    courseprogram: ''
  })
  const [errors, setErrors] = useState<{[key: string]: string}>({})
  const [courseSearch, setCourseSearch] = useState('')
  const [showDropdown, setShowDropdown] = useState(false)
  const [filteredCourses, setFilteredCourses] = useState(courses)

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value } = e.target
    setFormData(prev => ({ ...prev, [name]: value }))
    
    if (errors[name]) {
      setErrors(prev => ({ ...prev, [name]: '' }))
    }
  }

  const handleCourseSearch = (value: string) => {
    setCourseSearch(value)
    setFormData(prev => ({ ...prev, courseprogram: value }))
    
    const filtered = courses.filter(course => 
      course.toLowerCase().includes(value.toLowerCase())
    )
    setFilteredCourses(filtered)
    setShowDropdown(value.length > 0)
  }

  const selectCourse = (course: string) => {
    setCourseSearch(course)
    setFormData(prev => ({ ...prev, courseprogram: course }))
    setShowDropdown(false)
  }

  const validateForm = () => {
    const newErrors: {[key: string]: string} = {}

    if (!formData.fullname.trim()) {
      newErrors.fullname = 'Full name is required.'
    }

    if (!formData.studentid.trim()) {
      newErrors.studentid = 'Student ID is required.'
    }

    if (!formData.yearlevel) {
      newErrors.yearlevel = 'Year level is required.'
    }

    if (!formData.courseprogram.trim()) {
      newErrors.courseprogram = 'Please select a course/program from the list.'
    } else {
      const normalizedCourse = formData.courseprogram.toLowerCase().trim()
      const isValidCourse = courses.some(course => 
        course.toLowerCase().trim() === normalizedCourse
      )
      if (!isValidCourse) {
        newErrors.courseprogram = 'Invalid course/program. Please select from the dropdown list.'
      }
    }

    setErrors(newErrors)
    return Object.keys(newErrors).length === 0
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    
    if (!validateForm()) return

    // Store data in sessionStorage for next step
    sessionStorage.setItem('studentData', JSON.stringify(formData))
    
    // Redirect to step 2
    router.push('/student/queue-request-2')
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
        <form onSubmit={handleSubmit} className="bg-white rounded-lg shadow-lg max-w-xl w-full p-8">
          <div className="flex justify-center mb-6">
            <div className="bg-yellow-100 rounded-full p-4">
              <i className="fas fa-ticket-alt text-yellow-400 text-xl"></i>
            </div>
          </div>
          <h2 className="text-blue-900 font-extrabold text-xl text-center mb-2">Request Your Queue Number</h2>
          <p className="text-center text-slate-600 mb-6 text-sm">Please provide the following information to get your queue number</p>
          
          <div className="flex items-center justify-between text-xs md:text-sm mb-4">
            <span className="font-semibold text-blue-900">Step 1 of 3</span>
            <span className="text-gray-500">Service Request</span>
          </div>
          <div className="w-full h-1 rounded-full bg-slate-300 mb-6 relative">
            <div className="h-1 rounded-full bg-yellow-400 w-[33%]"></div>
          </div>
          <hr className="border-slate-200 mb-6"/>
          
          <h3 className="text-blue-900 font-semibold mb-4 text-sm">Student Information</h3>
          
          <label className="block mb-1 text-sm font-normal text-slate-900" htmlFor="fullname">
            Full Name <span className="text-red-600">*</span>
          </label>
          <input 
            className="w-full px-3 py-2 border border-slate-300 rounded-md text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400" 
            id="fullname" 
            name="fullname" 
            placeholder="Enter your complete name (Last, First, Middle)" 
            required 
            type="text" 
            value={formData.fullname}
            onChange={handleInputChange}
          />
          {errors.fullname && <p className="text-xs text-red-600 mt-1 mb-6">{errors.fullname}</p>}
          {!errors.fullname && <div className="mb-6"></div>}
          
          <div className="flex flex-col sm:flex-row sm:space-x-6 mb-6">
            <div className="flex-1">
              <label className="block mb-1 text-sm font-normal text-slate-900" htmlFor="studentid">
                Student ID Number <span className="text-red-600">*</span>
              </label>
              <input 
                className="w-full px-3 py-2 border border-slate-300 rounded-md text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400" 
                id="studentid" 
                name="studentid" 
                placeholder="e.g., 21411277" 
                required 
                type="text" 
                value={formData.studentid}
                onChange={handleInputChange}
              />
              <p className="text-xs text-slate-500 mt-1">Enter your official university ID number</p>
              {errors.studentid && <p className="text-xs text-red-600 mt-1">{errors.studentid}</p>}
            </div>
            <div className="flex-1 mt-4 sm:mt-0">
              <label className="block mb-1 text-sm font-normal text-slate-900" htmlFor="yearlevel">
                Year Level <span className="text-red-600">*</span>
              </label>
              <select 
                className="w-full px-3 py-2 border border-slate-300 rounded-md text-sm text-black focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400" 
                id="yearlevel" 
                name="yearlevel" 
                required
                value={formData.yearlevel}
                onChange={handleInputChange}
              >
                <option value="">Select year level</option>
                <option value="1st Year">1st Year</option>
                <option value="2nd Year">2nd Year</option>
                <option value="3rd Year">3rd Year</option>
                <option value="4th Year">4th Year</option>
              </select>
              {errors.yearlevel && <p className="text-xs text-red-600 mt-1">{errors.yearlevel}</p>}
            </div>
          </div>
          
          <label className="block mb-1 text-sm font-normal text-slate-900" htmlFor="courseSearch">
            Course/Program <span className="text-red-600">*</span>
          </label>
          <div className="relative mb-8">
            <input 
              className="w-full px-3 py-2 border border-slate-300 rounded-md text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400" 
              id="courseSearch" 
              placeholder="Type to search Course/Program" 
              type="text" 
              value={courseSearch}
              onChange={(e) => handleCourseSearch(e.target.value)}
              onFocus={() => setShowDropdown(courseSearch.length > 0)}
            />
            <div className="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-500">
              <i className="fas fa-chevron-down"></i>
            </div>
            {showDropdown && (
              <ul className="absolute z-10 w-full max-h-48 mt-1 overflow-auto bg-white border border-slate-300 rounded-md shadow-lg">
                {filteredCourses.map((course, index) => (
                  <li 
                    key={index}
                    className="cursor-pointer px-3 py-2 hover:bg-yellow-100"
                    onClick={() => selectCourse(course)}
                  >
                    {course}
                  </li>
                ))}
              </ul>
            )}
            {errors.courseprogram && <p className="text-xs text-red-600 mt-1">{errors.courseprogram}</p>}
          </div>
          
          <div className="flex justify-center" style={{gap: '80px'}}>
            <Link href="/student" className="flex items-center gap-2 border border-slate-300 rounded-md text-slate-700 text-sm hover:bg-slate-100 transition font-medium" style={{padding: '16px 32px', width: '130px', height: '36px', justifyContent: 'center'}}>
              <i className="fas fa-home text-sm"></i>
              Home
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
