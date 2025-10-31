import jwt from 'jsonwebtoken';
import bcrypt from 'bcryptjs';
import { cookies } from 'next/headers';
import { redirect } from 'next/navigation';

const JWT_SECRET = process.env.JWT_SECRET || 'your-secret-key';

export interface User {
  id: number;
  username: string;
  email: string;
  full_name: string;
  role: 'admin' | 'staff';
}

export interface AuthResult {
  success: boolean;
  user?: User;
  token?: string;
  error?: string;
}

// Hash password
export async function hashPassword(password: string): Promise<string> {
  return bcrypt.hash(password, 12);
}

// Verify password
export async function verifyPassword(password: string, hashedPassword: string): Promise<boolean> {
  return bcrypt.compare(password, hashedPassword);
}

// Generate JWT token
export function generateToken(user: User): string {
  return jwt.sign(
    { 
      id: user.id, 
      username: user.username, 
      role: user.role 
    },
    JWT_SECRET,
    { expiresIn: '24h' }
  );
}

// Verify JWT token
export function verifyToken(token: string): User | null {
  try {
    const decoded = jwt.verify(token, JWT_SECRET) as any;
    return {
      id: decoded.id,
      username: decoded.username,
      email: '', // Will be fetched from database
      full_name: '', // Will be fetched from database
      role: decoded.role
    };
  } catch (error) {
    return null;
  }
}

// Get user from token
export async function getUserFromToken(): Promise<User | null> {
  const cookieStore = cookies();
  const token = cookieStore.get('auth-token')?.value;
  
  if (!token) return null;
  
  const user = verifyToken(token);
  if (!user) return null;
  
  // Fetch full user data from database
  const { getAdminUserById } = await import('./database');
  const fullUser = await getAdminUserById(user.id);
  
  if (!fullUser) return null;
  
  return {
    id: fullUser.id,
    username: fullUser.username,
    email: fullUser.email,
    full_name: fullUser.full_name,
    role: fullUser.role
  };
}

// Set auth cookie
export function setAuthCookie(token: string) {
  const cookieStore = cookies();
  cookieStore.set('auth-token', token, {
    httpOnly: true,
    secure: process.env.NODE_ENV === 'production',
    sameSite: 'lax',
    maxAge: 24 * 60 * 60 // 24 hours
  });
}

// Clear auth cookie
export function clearAuthCookie() {
  const cookieStore = cookies();
  cookieStore.delete('auth-token');
}

// Require authentication middleware
export async function requireAuth(): Promise<User> {
  const user = await getUserFromToken();
  
  if (!user) {
    redirect('/personnel/signin');
  }
  
  return user;
}

// Require admin role middleware
export async function requireAdmin(): Promise<User> {
  const user = await requireAuth();
  
  if (user.role !== 'admin') {
    redirect('/personnel/working/queue');
  }
  
  return user;
}

// Require staff role middleware
export async function requireStaff(): Promise<User> {
  const user = await requireAuth();
  
  if (user.role !== 'staff') {
    redirect('/personnel/admin/dashboard');
  }
  
  return user;
}

// Login function
export async function login(username: string, password: string): Promise<AuthResult> {
  try {
    const { getAdminUserByUsername } = await import('./database');
    const user = await getAdminUserByUsername(username);
    
    if (!user) {
      return { success: false, error: 'Invalid credentials' };
    }
    
    const isValidPassword = await verifyPassword(password, user.password_hash);
    
    if (!isValidPassword) {
      return { success: false, error: 'Invalid credentials' };
    }
    
    const userData: User = {
      id: user.id,
      username: user.username,
      email: user.email,
      full_name: user.full_name,
      role: user.role
    };
    
    const token = generateToken(userData);
    setAuthCookie(token);
    
    return { success: true, user: userData, token };
  } catch (error) {
    console.error('Login error:', error);
    return { success: false, error: 'Login failed' };
  }
}

// Logout function
export function logout() {
  clearAuthCookie();
  redirect('/personnel/signin');
}
