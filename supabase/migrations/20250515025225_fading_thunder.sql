/*
  # Add bank transactions table and functions

  1. New Tables
    - `bank_transactions`
      - `id` (uuid, primary key)
      - `user_id` (uuid, references users)
      - `amount` (bigint)
      - `type` (text - 'deposit' or 'withdraw')
      - `created_at` (timestamptz)
      
  2. Security
    - Enable RLS on `bank_transactions` table
    - Add policies for users to read their own transactions
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