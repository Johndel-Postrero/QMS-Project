import { requireAdmin } from '@/lib/auth'
import AdminDashboard from '@/components/AdminDashboard'

export default async function AdminDashboardPage() {
  const user = await requireAdmin()

  return <AdminDashboard user={user} />
}
