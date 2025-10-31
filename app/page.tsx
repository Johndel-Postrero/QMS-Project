import { redirect } from 'next/navigation'
import { getUserFromToken } from '@/lib/auth'

export default async function HomePage() {
  const user = await getUserFromToken()
  
  if (user) {
    // Redirect based on user role
    if (user.role === 'admin') {
      redirect('/personnel/admin/dashboard')
    } else if (user.role === 'staff') {
      redirect('/personnel/working/queue')
    }
  }
  
  // Default redirect to student interface
  redirect('/student')
}
