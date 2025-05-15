/*
  # Initial Schema Setup

  1. New Tables
    - `users`
      - `id` (uuid, primary key) - User's unique identifier
      - `username` (text) - User's display name
      - `email` (text) - User's email address
      - `money` (bigint) - User's current money
      - `bank_money` (bigint) - Money in bank
      - `health` (int) - Current health points
      - `max_health` (int) - Maximum health points
      - `energy` (int) - Current energy points
      - `max_energy` (int) - Maximum energy points
      - `nerve` (int) - Current nerve points
      - `max_nerve` (int) - Maximum nerve points
      - `level` (int) - User's current level
      - `exp` (bigint) - Experience points
      - `created_at` (timestamptz) - Account creation date
      - `last_active` (timestamptz) - Last activity timestamp
    
    - `gangs`
      - `id` (uuid, primary key) - Gang's unique identifier
      - `name` (text) - Gang name
      - `tag` (text) - Gang tag/abbreviation
      - `leader_id` (uuid) - Gang leader's user ID
      - `money` (bigint) - Gang's money vault
      - `level` (int) - Gang's level
      - `exp` (bigint) - Gang's experience points
      - `created_at` (timestamptz) - Gang creation date

  2. Security
    - Enable RLS on all tables
    - Add policies for authenticated users
*/

-- Create users table
CREATE TABLE users (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  username text UNIQUE NOT NULL,
  email text UNIQUE NOT NULL,
  money bigint DEFAULT 1000,
  bank_money bigint DEFAULT 0,
  health int DEFAULT 100,
  max_health int DEFAULT 100,
  energy int DEFAULT 10,
  max_energy int DEFAULT 10,
  nerve int DEFAULT 5,
  max_nerve int DEFAULT 5,
  level int DEFAULT 1,
  exp bigint DEFAULT 0,
  created_at timestamptz DEFAULT now(),
  last_active timestamptz DEFAULT now()
);

-- Create gangs table
CREATE TABLE gangs (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  name text UNIQUE NOT NULL,
  tag text UNIQUE NOT NULL,
  leader_id uuid REFERENCES users(id),
  money bigint DEFAULT 0,
  level int DEFAULT 1,
  exp bigint DEFAULT 0,
  created_at timestamptz DEFAULT now()
);

-- Add gang_id to users table
ALTER TABLE users ADD COLUMN gang_id uuid REFERENCES gangs(id);

-- Enable RLS
ALTER TABLE users ENABLE ROW LEVEL SECURITY;
ALTER TABLE gangs ENABLE ROW LEVEL SECURITY;

-- User policies
CREATE POLICY "Users can read their own data" 
  ON users 
  FOR SELECT 
  TO authenticated 
  USING (auth.uid() = id);

CREATE POLICY "Users can update their own data"
  ON users
  FOR UPDATE
  TO authenticated
  USING (auth.uid() = id);

-- Gang policies
CREATE POLICY "Anyone can read gang data"
  ON gangs
  FOR SELECT
  TO authenticated
  USING (true);

CREATE POLICY "Only gang leader can update gang"
  ON gangs
  FOR UPDATE
  TO authenticated
  USING (auth.uid() = leader_id);