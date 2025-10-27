import Link from 'next/link'
import Image from 'next/image'

export default function StudentLanding() {
  return (
    <div className="min-h-screen bg-white text-gray-700">
      {/* Header */}
      <header className="bg-white border-b border-gray-300">
        <div className="flex items-center justify-between py-3 px-6 md:px-10 mx-20 md:mx-34 lg:mx-44">
          <Link href="/student" className="flex items-center hover:opacity-80 transition-opacity">
            <Image 
              alt="University of Cebu Student Affairs circular seal" 
              className="h-12 w-12 rounded-full object-cover" 
              src="/sao-nobg.png"
              width={48}
              height={48}
            />
            <div className="ml-4 text-left">
              <h1 className="text-blue-900 font-bold text-xl -mb-1">
                SeQueueR
              </h1>
              <p className="text-gray-600 text-sm">
                UC Student Affairs
              </p>
            </div>
          </Link>
          <Link href="/personnel/signin" className="bg-yellow-400 text-black font-semibold text-sm px-5 py-2 rounded-md hover:bg-yellow-300 transition">
            Login
          </Link>
        </div>
      </header>

      {/* Main Content */}
      <main className="bg-[#00447a] text-white flex items-center" style={{height: 'calc(100vh - 80px)'}}>
        <div className="flex flex-col md:flex-row items-center justify-between px-6 md:px-10 gap-12 md:gap-20 w-full mx-20 md:mx-34 lg:mx-44">
          <div className="flex-1 space-y-6">
            <h2 className="text-[48px] font-extrabold leading-tight">
              Welcome to <span className="text-yellow-400">SeQueueR</span>
            </h2>
            <p className="text-[24px] font-semibold leading-relaxed" style={{maxWidth: '800px'}}>
              Smart Queue Management for University of Cebu Student Affairs Services
            </p>
            <p className="text-[18px] font-light leading-relaxed" style={{maxWidth: '600px'}}>
              Skip the long lines. Get your queue number instantly and track your turn in real-time. Make your student affairs visits more efficient and stress-free.
            </p>
            <div className="flex flex-wrap" style={{gap: '30px'}}>
              <Link href="/student/queue-request" className="bg-yellow-400 text-black font-semibold rounded-md shadow-md flex items-center gap-2 hover:brightness-110 transition text-[18px]" style={{padding: '12px 24px', width: '260px', height: '50px', justifyContent: 'center'}}>
                <i className="fas fa-laptop text-sm"></i>
                Get Queue Number
              </Link>
              <Link href="/student/about" className="border border-white text-white font-semibold rounded-md flex items-center gap-2 hover:bg-white hover:text-[#00447a] transition text-[18px]" style={{padding: '12px 24px', width: '260px', height: '50px', justifyContent: 'center'}}>
                <i className="far fa-clock text-sm"></i>
                About SeQueueR
              </Link>
            </div>
          </div>
          <div className="flex-1 flex justify-center md:justify-end">
            <div className="w-[320px] h-[320px] sm:w-[370px] sm:h-[370px] md:w-[420px] md:h-[420px] lg:w-[477px] lg:h-[477px] rounded-full border-8 border-white shadow-2xl flex items-center justify-center bg-white">
              <Image 
                alt="University of Cebu Student Affairs Office logo" 
                className="w-[300px] h-[300px] sm:w-[350px] sm:h-[350px] md:w-[400px] md:h-[400px] lg:w-[457px] lg:h-[457px] object-cover rounded-full" 
                src="/sao-logo.jpg"
                width={457}
                height={457}
              />
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
