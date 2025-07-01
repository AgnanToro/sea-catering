// Security utilities for Level 4 requirements

export interface PasswordValidation {
  isValid: boolean;
  errors: string[];
}

export interface SecurityConfig {
  passwordMinLength: number;
  requireUppercase: boolean;
  requireLowercase: boolean;
  requireNumbers: boolean;
  requireSpecialChars: boolean;
}

export const securityConfig: SecurityConfig = {
  passwordMinLength: 8,
  requireUppercase: true,
  requireLowercase: true,
  requireNumbers: true,
  requireSpecialChars: true
};

// Enhanced password validation for Level 4 security
export const validatePassword = (password: string): PasswordValidation => {
  const errors: string[] = [];
  
  if (password.length < securityConfig.passwordMinLength) {
    errors.push(`Password minimal ${securityConfig.passwordMinLength} karakter`);
  }
  
  if (securityConfig.requireUppercase && !/[A-Z]/.test(password)) {
    errors.push('Password harus mengandung huruf besar');
  }
  
  if (securityConfig.requireLowercase && !/[a-z]/.test(password)) {
    errors.push('Password harus mengandung huruf kecil');
  }
  
  if (securityConfig.requireNumbers && !/\d/.test(password)) {
    errors.push('Password harus mengandung angka');
  }
  
  if (securityConfig.requireSpecialChars && !/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
    errors.push('Password harus mengandung karakter khusus (!@#$%^&*)');
  }
  
  return {
    isValid: errors.length === 0,
    errors
  };
};

// XSS Prevention - Sanitize user input
export const sanitizeInput = (input: string): string => {
  return input
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#x27;')
    .replace(/\//g, '&#x2F;');
};

// SQL Injection Prevention - Basic validation
export const validateSqlInput = (input: string): boolean => {
  const sqlInjectionPatterns = [
    /('|(\\')|(;)|(\\)|(\\\\)|(\\;))/i,
    /((\%27)|(\'))((\%6F)|o|(\%4F))((\%72)|r|(\%52))/i,
    /((\%27)|(\'))((\%55)|u|(\%75))((\%6E)|n|(\%4E))((\%69)|i|(\%49))((\%6F)|o|(\%4F))((\%6E)|n|(\%4E))/i,
    /((\%27)|(\'))((\%73)|s|(\%53))((\%65)|e|(\%45))((\%6C)|l|(\%4C))((\%65)|e|(\%45))((\%63)|c|(\%43))((\%74)|t|(\%54))/i,
    /((\%27)|(\'))((\%69)|i|(\%49))((\%6E)|n|(\%4E))((\%73)|s|(\%53))((\%65)|e|(\%45))((\%72)|r|(\%52))((\%74)|t|(\%54))/i,
    /((\%27)|(\'))((\%64)|d|(\%44))((\%65)|e|(\%45))((\%6C)|l|(\%4C))((\%65)|e|(\%45))((\%74)|t|(\%54))((\%65)|e|(\%45))/i,
    /((\%27)|(\'))((\%75)|u|(\%55))((\%70)|p|(\%50))((\%64)|d|(\%44))((\%61)|a|(\%41))((\%74)|t|(\%54))((\%65)|e|(\%45))/i,
    /((\%27)|(\'))((\%64)|d|(\%44))((\%72)|r|(\%52))((\%6F)|o|(\%4F))((\%70)|p|(\%50))/i,
    /exec(\s|\+)+(s|x)p\w+/i,
    /UNION.*SELECT/i,
    /DROP.*TABLE/i
  ];
  
  return !sqlInjectionPatterns.some(pattern => pattern.test(input));
};

// Generate CSRF token
export const generateCSRFToken = (): string => {
  return Math.random().toString(36).substring(2) + Date.now().toString(36);
};

// Hash password (simple implementation for demo - in production use bcrypt)
export const hashPassword = (password: string): string => {
  // This is a simple hash for demo purposes
  // In production, use bcrypt or similar
  let hash = 0;
  for (let i = 0; i < password.length; i++) {
    const char = password.charCodeAt(i);
    hash = ((hash << 5) - hash) + char;
    hash = hash & hash; // Convert to 32bit integer
  }
  return 'hash_' + Math.abs(hash).toString(16);
};

// Verify hashed password
export const verifyPassword = (password: string, hash: string): boolean => {
  return hashPassword(password) === hash;
};

// Enhanced email validation
export const validateEmailSecurity = (email: string): { isValid: boolean; error?: string } => {
  // Check for basic email format
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    return { isValid: false, error: 'Format email tidak valid' };
  }
  
  // Check for potential XSS in email
  if (!validateSqlInput(email)) {
    return { isValid: false, error: 'Email mengandung karakter tidak valid' };
  }
  
  // Check email length
  if (email.length > 254) {
    return { isValid: false, error: 'Email terlalu panjang' };
  }
  
  return { isValid: true };
};

// Enhanced phone validation
export const validatePhoneSecurity = (phone: string): { isValid: boolean; error?: string } => {
  // Check Indonesian phone number patterns
  const phonePatterns = [
    /^(\+62|62|0)8[1-9][0-9]{6,9}$/, // Indonesian mobile
    /^(\+62|62|0)[2-9][0-9]{7,10}$/ // Indonesian landline
  ];
  
  const isValidFormat = phonePatterns.some(pattern => pattern.test(phone));
  if (!isValidFormat) {
    return { isValid: false, error: 'Format nomor HP tidak valid' };
  }
  
  // Check for SQL injection attempts
  if (!validateSqlInput(phone)) {
    return { isValid: false, error: 'Nomor HP mengandung karakter tidak valid' };
  }
  
  return { isValid: true };
};
