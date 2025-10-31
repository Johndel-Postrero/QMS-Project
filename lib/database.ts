import { sql } from '@vercel/postgres';

export interface QueueRequest {
  id: number;
  student_id: string;
  full_name: string;
  year_level: string;
  course_program: string;
  queue_number: string;
  status: 'active' | 'completed' | 'cancelled';
  created_at: Date;
  updated_at: Date;
}

export interface AdminUser {
  id: number;
  username: string;
  email: string;
  password_hash: string;
  full_name: string;
  role: 'admin' | 'staff';
  is_active: boolean;
  created_at: Date;
  updated_at: Date;
}

export interface QueueSettings {
  id: number;
  setting_key: string;
  setting_value: string;
  description: string;
  updated_at: Date;
}

// Queue Requests Functions
export async function createQueueRequest(data: Omit<QueueRequest, 'id' | 'created_at' | 'updated_at'>) {
  const { student_id, full_name, year_level, course_program, queue_number, status } = data;
  
  const result = await sql`
    INSERT INTO queue_requests (student_id, full_name, year_level, course_program, queue_number, status)
    VALUES (${student_id}, ${full_name}, ${year_level}, ${course_program}, ${queue_number}, ${status})
    RETURNING *
  `;
  
  return result.rows[0];
}

export async function getQueueRequests() {
  const result = await sql`SELECT * FROM queue_requests ORDER BY created_at DESC`;
  return result.rows;
}

export async function getActiveQueues() {
  const result = await sql`SELECT * FROM active_queues`;
  return result.rows;
}

export async function getQueueRequestById(id: number) {
  const result = await sql`SELECT * FROM queue_requests WHERE id = ${id}`;
  return result.rows[0];
}

export async function updateQueueRequestStatus(id: number, status: 'active' | 'completed' | 'cancelled') {
  const result = await sql`
    UPDATE queue_requests 
    SET status = ${status}, updated_at = CURRENT_TIMESTAMP 
    WHERE id = ${id}
    RETURNING *
  `;
  return result.rows[0];
}

export async function getNextQueueNumber() {
  const result = await sql`
    SELECT COALESCE(MAX(CAST(SUBSTRING(queue_number FROM '[0-9]+') AS INTEGER)), 0) + 1 as next_number
    FROM queue_requests 
    WHERE DATE(created_at) = CURRENT_DATE
  `;
  return result.rows[0]?.next_number || 1;
}

// Admin Users Functions
export async function getAdminUserByUsername(username: string) {
  const result = await sql`SELECT * FROM admin_users WHERE username = ${username} AND is_active = true`;
  return result.rows[0];
}

export async function getAdminUserById(id: number) {
  const result = await sql`SELECT * FROM admin_users WHERE id = ${id} AND is_active = true`;
  return result.rows[0];
}

export async function createAdminUser(data: Omit<AdminUser, 'id' | 'created_at' | 'updated_at'>) {
  const { username, email, password_hash, full_name, role, is_active } = data;
  
  const result = await sql`
    INSERT INTO admin_users (username, email, password_hash, full_name, role, is_active)
    VALUES (${username}, ${email}, ${password_hash}, ${full_name}, ${role}, ${is_active})
    RETURNING *
  `;
  
  return result.rows[0];
}

// Queue Settings Functions
export async function getQueueSettings() {
  const result = await sql`SELECT * FROM queue_settings`;
  return result.rows;
}

export async function getQueueSetting(key: string) {
  const result = await sql`SELECT * FROM queue_settings WHERE setting_key = ${key}`;
  return result.rows[0];
}

export async function updateQueueSetting(key: string, value: string) {
  const result = await sql`
    UPDATE queue_settings 
    SET setting_value = ${value}, updated_at = CURRENT_TIMESTAMP 
    WHERE setting_key = ${key}
    RETURNING *
  `;
  return result.rows[0];
}

// Dashboard Statistics
export async function getDashboardStats() {
  const today = new Date().toISOString().split('T')[0];
  
  const stats = await sql`
    SELECT 
      COUNT(*) as total_queues_today,
      COUNT(CASE WHEN status = 'active' THEN 1 END) as active_queues,
      COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_queues,
      COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled_queues
    FROM queue_requests 
    WHERE DATE(created_at) = ${today}
  `;
  
  const currentServing = await sql`
    SELECT queue_number, full_name, student_id
    FROM queue_requests 
    WHERE status = 'active' 
    ORDER BY created_at ASC 
    LIMIT 1
  `;
  
  return {
    ...stats.rows[0],
    currently_serving: currentServing.rows[0] || null
  };
}

export async function getRecentActivity(limit: number = 10) {
  const result = await sql`
    SELECT 
      queue_number,
      full_name,
      student_id,
      course_program,
      status,
      created_at
    FROM queue_requests 
    ORDER BY created_at DESC 
    LIMIT ${limit}
  `;
  return result.rows;
}
