'use client'

import { useState } from 'react'
import { useRouter } from 'next/navigation'
import Link from 'next/link'

export default function PersonnelSignin() {
  const router = useRouter()
  const [formData, setFormData] = useState({
    username: '',
    password: '',
    remember: false
  })
  const [isLoading, setIsLoading] = useState(false)
  const [error, setError] = useState('')
  const [showPassword, setShowPassword] = useState(false)

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value, type, checked } = e.target
    setFormData(prev => ({
      ...prev,
      [name]: type === 'checkbox' ? checked : value
    }))
    
    if (error) setError('')
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setIsLoading(true)
    setError('')

    try {
      const response = await fetch('/api/auth/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          username: formData.username,
          password: formData.password,
          remember: formData.remember
        }),
      })

      const data = await response.json()

      if (!response.ok) {
        throw new Error(data.error || 'Login failed')
      }

      // Redirect based on user role
      if (data.user.role === 'admin') {
        router.push('/personnel/admin/dashboard')
      } else if (data.user.role === 'staff') {
        router.push('/personnel/working/queue')
      } else {
        router.push('/personnel')
      }

    } catch (error: any) {
      setError(error.message)
    } finally {
      setIsLoading(false)
    }
  }

  return (
    <div className="min-h-screen flex flex-col bg-white text-gray-700">
      {/* Header */}
      <header className="bg-white border-b border-gray-300">
        <div className="flex items-center justify-between py-3 px-6 md:px-10 mx-20 md:mx-34 lg:mx-44">
          <Link href="/personnel" className="flex items-center hover:opacity-80 transition-opacity">
            <img alt="University of Cebu Student Affairs circular seal" className="h-12 w-12 rounded-full object-cover" src="/sao-nobg.png"/>
            <div className="ml-4 text-left">
              <h1 className="text-blue-900 font-bold text-xl -mb-1">SeQueueR</h1>
              <p className="text-gray-600 text-sm">UC Student Affairs</p>
            </div>
          </Link>
          <Link href="/student" className="bg-yellow-400 text-black font-semibold text-sm px-5 py-2 rounded-md hover:bg-yellow-300 transition">
            Student Portal
          </Link>
        </div>
      </header>

      {/* Main Content */}
      <main className="flex-grow flex justify-center items-center bg-gradient-to-r from-white via-slate-300 to-slate-600 relative overflow-hidden py-12">
        <img 
          alt="University of Cebu campus buildings with modern architecture, blue sky, and trees, faded and tinted blue as background" 
          className="absolute inset-0 w-full h-full object-cover opacity-70 pointer-events-none select-none" 
          src="https://placehold.co/1920x1080/png?text=University+of+Cebu+Campus+Buildings+Background"
        />
        
        <form onSubmit={handleSubmit} className="relative bg-white rounded-lg shadow-md max-w-xl w-full p-10 space-y-6">
          <div className="flex justify-center">
            <div className="bg-yellow-400 rounded-full p-4">
              <i className="fas fa-user-graduate text-blue-700 text-xl"></i>
            </div>
          </div>
          <h2 className="text-center text-blue-700 font-extrabold text-xl">SeQueueR Login</h2>
          <p className="text-center text-gray-600 text-sm">Sign in to your queue management dashboard</p>
          
          {error && (
            <div className="bg-red-50 border border-red-200 rounded-md p-3">
              <p className="text-red-600 text-sm">{error}</p>
            </div>
          )}
          
          <div>
            <label className="block text-blue-700 font-semibold text-sm mb-1" htmlFor="username">User Name</label>
            <div className="relative">
              <span className="absolute inset-y-0 left-3 flex items-center text-blue-700">
                <i className="fas fa-id-badge"></i>
              </span>
              <input 
                autoComplete="username" 
                className="w-full border border-gray-300 rounded-md py-2 pl-10 pr-3 text-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" 
                id="username" 
                name="username" 
                placeholder="e.g., WS2024-001" 
                type="text"
                value={formData.username}
                onChange={handleInputChange}
                required
              />
            </div>
          </div>
          
          <div>
            <label className="block text-blue-700 font-semibold text-sm mb-1" htmlFor="password">Password</label>
            <div className="relative">
              <span className="absolute inset-y-0 left-3 flex items-center text-blue-700">
                <i className="fas fa-lock"></i>
              </span>
              <input 
                autoComplete="current-password" 
                className="w-full border border-gray-300 rounded-md py-2 pl-10 pr-10 text-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" 
                id="password" 
                name="password" 
                placeholder="Enter your password" 
                type={showPassword ? "text" : "password"}
                value={formData.password}
                onChange={handleInputChange}
                required
              />
              <span 
                className="absolute inset-y-0 right-3 flex items-center text-gray-400 cursor-pointer" 
                onClick={() => setShowPassword(!showPassword)}
              >
                <i className={`fas ${showPassword ? 'fa-eye-slash' : 'fa-eye'}`}></i>
              </span>
            </div>
          </div>
          
          <div className="flex items-center space-x-2">
            <input 
              className="w-4 h-4 border border-gray-400 rounded-sm text-blue-600 focus:ring-blue-500" 
              id="remember" 
              name="remember" 
              type="checkbox"
              checked={formData.remember}
              onChange={handleInputChange}
            />
            <label className="text-sm text-gray-700 select-none" htmlFor="remember">Remember me</label>
          </div>
          <p className="text-xs text-gray-500">Only use on trusted office computers</p>
          
          <button 
            className="w-full bg-blue-900 text-white font-medium rounded-md py-3 mt-2 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-700 disabled:opacity-50 disabled:cursor-not-allowed" 
            type="submit"
            disabled={isLoading}
          >
            {isLoading ? (
              <div className="flex items-center justify-center">
                <div className="loading-spinner mr-2"></div>
                Logging in...
              </div>
            ) : (
              'Login'
            )}
          </button>
          
          <div className="flex justify-end">
            <Link className="text-blue-600 text-sm hover:underline" href="/personnel/forgot-password">Forgot Password?</Link>
          </div>
          <p className="text-center text-xs text-gray-600">First time login? Check with supervisor</p>
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
