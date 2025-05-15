/*
  # Bank System Implementation

  1. Changes
    - Add bank transactions table if not exists
    - Add bank account status to users table
    - Add bank transaction functions
    
  2. Security
    - Enable RLS on bank_transactions table
    - Add policy for users to read their own transactions
*/

-- Create bank transactions table if it doesn't exist
DO $$ BEGIN
  CREATE TABLE IF NOT EXISTS bank_transactions (
    id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id uuid REFERENCES users(id) NOT NULL,
    amount bigint NOT NULL,
    type text NOT NULL CHECK (type IN ('deposit', 'withdraw')),
    created_at timestamptz DEFAULT now()
  );
EXCEPTION
  WHEN duplicate_table THEN
    NULL;
END $$;

-- Enable RLS if not already enabled
ALTER TABLE IF EXISTS bank_transactions ENABLE ROW LEVEL SECURITY;

-- Drop existing policy if it exists and create new one
DO $$ BEGIN
  DROP POLICY IF EXISTS "Users can read their own transactions" ON bank_transactions;
  CREATE POLICY "Users can read their own transactions"
    ON bank_transactions
    FOR SELECT
    TO authenticated
    USING (auth.uid() = user_id);
EXCEPTION
  WHEN undefined_object THEN
    NULL;
END $$;

-- Add bank account status to users table if column doesn't exist
DO $$ BEGIN
  ALTER TABLE users ADD COLUMN IF NOT EXISTS has_bank_account boolean DEFAULT false;
EXCEPTION
  WHEN duplicate_column THEN
    NULL;
END $$;

-- Drop existing functions if they exist
DROP FUNCTION IF EXISTS open_bank_account(uuid, bigint);
DROP FUNCTION IF EXISTS deposit_money(uuid, bigint);
DROP FUNCTION IF EXISTS withdraw_money(uuid, bigint);

-- Create function to open a bank account
CREATE OR REPLACE FUNCTION open_bank_account(user_id uuid, fee bigint)
RETURNS void AS $$
BEGIN
  -- Check if user has enough money
  IF (SELECT money FROM users WHERE id = user_id) < fee THEN
    RAISE EXCEPTION 'Not enough money to open a bank account';
  END IF;

  -- Update user's money and bank account status
  UPDATE users 
  SET 
    money = money - fee,
    has_bank_account = true
  WHERE id = user_id;
END;
$$ LANGUAGE plpgsql;

-- Create function to deposit money
CREATE OR REPLACE FUNCTION deposit_money(user_id uuid, amount bigint)
RETURNS void AS $$
BEGIN
  -- Check if user has a bank account
  IF NOT (SELECT has_bank_account FROM users WHERE id = user_id) THEN
    RAISE EXCEPTION 'You do not have a bank account';
  END IF;

  -- Check if user has enough money
  IF (SELECT money FROM users WHERE id = user_id) < amount THEN
    RAISE EXCEPTION 'Not enough money to deposit';
  END IF;

  -- Update user's money and bank money
  UPDATE users 
  SET 
    money = money - amount,
    bank_money = bank_money + amount
  WHERE id = user_id;

  -- Record the transaction
  INSERT INTO bank_transactions (user_id, amount, type)
  VALUES (user_id, amount, 'deposit');
END;
$$ LANGUAGE plpgsql;

-- Create function to withdraw money
CREATE OR REPLACE FUNCTION withdraw_money(user_id uuid, amount bigint)
RETURNS void AS $$
BEGIN
  -- Check if user has a bank account
  IF NOT (SELECT has_bank_account FROM users WHERE id = user_id) THEN
    RAISE EXCEPTION 'You do not have a bank account';
  END IF;

  -- Check if user has enough money in bank
  IF (SELECT bank_money FROM users WHERE id = user_id) < amount THEN
    RAISE EXCEPTION 'Not enough money in bank to withdraw';
  END IF;

  -- Update user's money and bank money
  UPDATE users 
  SET 
    money = money + amount,
    bank_money = bank_money - amount
  WHERE id = user_id;

  -- Record the transaction
  INSERT INTO bank_transactions (user_id, amount, type)
  VALUES (user_id, amount, 'withdraw');
END;
$$ LANGUAGE plpgsql;