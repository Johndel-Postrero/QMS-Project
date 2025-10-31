import { requireStaff } from '@/lib/auth'
import WorkingQueue from '@/components/WorkingQueue'

export default async function WorkingQueuePage() {
  const user = await requireStaff()

  return <WorkingQueue user={user} />
}
