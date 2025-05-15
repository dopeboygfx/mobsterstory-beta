/*
  # Bank transactions and account system

  1. New Tables
    - `bank_transactions`
      - `id` (uuid, primary key)
      - `user_id` (uuid, references users.id)
      - `amount` (bigint)
      - `type` (text, either 'deposit' or 'withdraw')
      - `created_at` (timestamp)
  2. Changes
    - Add `has_bank_account` boolean column to users table
  3. Security
    - Enable RLS on `bank_transactions` table
    - Add policy for authenticated users to read their own transactions
*/

CREATE TABLE bank_transactions (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  user_id uuid REFERENCES users(id) NOT NULL,
  amount bigint NOT NULL,
  type text NOT NULL CHECK (type IN ('deposit', 'withdraw')),
  created_at timestamptz DEFAULT now()
);

ALTER TABLE bank_transactions ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Users can read their own transactions"
  ON bank_transactions
  FOR SELECT
  TO authenticated
  USING (auth.uid() = user_id);

-- Add bank account status to users table
ALTER TABLE users ADD COLUMN has_bank_account boolean DEFAULT false;

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