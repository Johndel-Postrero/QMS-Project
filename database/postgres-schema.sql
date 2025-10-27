-- QMS Database Schema for Vercel Postgres
-- Queue Management System for University of Cebu Student Affairs

-- Queue Requests Table
CREATE TABLE IF NOT EXISTS queue_requests (
    id SERIAL PRIMARY KEY,
    student_id VARCHAR(8) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    year_level VARCHAR(20) NOT NULL,
    course_program VARCHAR(100) NOT NULL,
    queue_number VARCHAR(10) NOT NULL,
    status VARCHAR(20) DEFAULT 'active' CHECK (status IN ('active', 'completed', 'cancelled')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS idx_queue_requests_student_id ON queue_requests(student_id);
CREATE INDEX IF NOT EXISTS idx_queue_requests_queue_number ON queue_requests(queue_number);
CREATE INDEX IF NOT EXISTS idx_queue_requests_status ON queue_requests(status);
CREATE INDEX IF NOT EXISTS idx_queue_requests_created_at ON queue_requests(created_at);
CREATE INDEX IF NOT EXISTS idx_queue_requests_course_program ON queue_requests(course_program);

-- Admin Users Table
CREATE TABLE IF NOT EXISTS admin_users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'staff' CHECK (role IN ('admin', 'staff')),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Queue Settings Table
CREATE TABLE IF NOT EXISTS queue_settings (
    id SERIAL PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default settings
INSERT INTO queue_settings (setting_key, setting_value, description) VALUES
('max_daily_queues', '500', 'Maximum number of queue requests per day'),
('queue_prefix', 'Q', 'Prefix for queue numbers'),
('office_hours_start', '08:00', 'Office opening time'),
('office_hours_end', '17:00', 'Office closing time'),
('auto_complete_hours', '24', 'Hours after which to auto-complete active queues')
ON CONFLICT (setting_key) DO NOTHING;

-- Sample admin user (password: admin123)
INSERT INTO admin_users (username, email, password_hash, full_name, role) VALUES
('admin', 'admin@uc.edu.ph', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin')
ON CONFLICT (username) DO NOTHING;

-- Create a view for active queues
CREATE OR REPLACE VIEW active_queues AS
SELECT 
    qr.id,
    qr.student_id,
    qr.full_name,
    qr.year_level,
    qr.course_program,
    qr.queue_number,
    qr.created_at,
    EXTRACT(EPOCH FROM (NOW() - qr.created_at))/60 as waiting_time_minutes
FROM queue_requests qr
WHERE qr.status = 'active'
ORDER BY qr.created_at ASC;

-- Create a view for daily statistics
CREATE OR REPLACE VIEW daily_queue_stats AS
SELECT 
    DATE(created_at) as queue_date,
    COUNT(*) as total_requests,
    COUNT(CASE WHEN status = 'active' THEN 1 END) as active_queues,
    COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_queues,
    COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled_queues,
    MIN(queue_number) as first_queue,
    MAX(queue_number) as last_queue
FROM queue_requests
GROUP BY DATE(created_at)
ORDER BY queue_date DESC;
