import { useState, useEffect } from 'react';
import { useSupabaseAuth } from '../hooks/useSupabaseAuth';
import { supabase } from '../lib/supabase';
import toast from 'react-hot-toast';
import { useNavigate } from 'react-router-dom';

export function Bank() {
  const { user } = useSupabaseAuth();
  const navigate = useNavigate();
  const [amount, setAmount] = useState('');
  const [loading, setLoading] = useState(false);
  const [userData, setUserData] = useState<{
    money: number;
    bank_money: number;
    has_bank_account: boolean;
  } | null>(null);

  const [transactions, setTransactions] = useState<{
    id: string;
    amount: number;
    type: 'deposit' | 'withdraw';
    created_at: string;
  }[]>([]);

  useEffect(() => {
    if (!user) {
      navigate('/auth');
      return;
    }
    
    fetchUserData();
  }, [user, navigate]);

  async function fetchUserData() {
    try {
      setLoading(true);
      const { data, error } = await supabase
        .from('users')
        .select('money, bank_money, has_bank_account')
        .eq('id', user?.id)
        .single();

      if (error) throw error;
      setUserData(data);

      // Only fetch transactions if user has a bank account
      if (data.has_bank_account) {
        const { data: transactionsData, error: transactionsError } = await supabase
          .from('bank_transactions')
          .select('*')
          .eq('user_id', user?.id)
          .order('created_at', { ascending: false })
          .limit(10);

        if (transactionsError) throw transactionsError;
        setTransactions(transactionsData);
      }
    } catch (error) {
      toast.error('Error loading bank data');
      console.error('Error:', error);
    } finally {
      setLoading(false);
    }
  }

  async function openAccount() {
    try {
      setLoading(true);
      const openingFee = 5000;

      if (!userData || userData.money < openingFee) {
        toast.error(`You need $${openingFee.toLocaleString()} to open an account`);
        return;
      }

      const { error } = await supabase.rpc('open_bank_account', {
        user_id: user?.id,
        fee: openingFee
      });

      if (error) throw error;

      toast.success('Bank account opened successfully!');
      fetchUserData();
    } catch (error) {
      toast.error('Error opening bank account');
      console.error('Error:', error);
    } finally {
      setLoading(false);
    }
  }

  async function handleTransaction(type: 'deposit' | 'withdraw') {
    try {
      if (!amount || isNaN(Number(amount)) || Number(amount) <= 0) {
        toast.error('Please enter a valid amount');
        return;
      }

      const numAmount = Number(amount);
      setLoading(true);

      if (type === 'deposit') {
        if (userData?.money && userData.money < numAmount) {
          toast.error('You don\'t have enough money');
          return;
        }

        const { error } = await supabase.rpc('deposit_money', {
          user_id: user?.id,
          amount: numAmount
        });

        if (error) throw error;
        toast.success(`Successfully deposited $${numAmount.toLocaleString()}`);
      } else {
        if (userData?.bank_money && userData.bank_money < numAmount) {
          toast.error('You don\'t have enough money in the bank');
          return;
        }

        const { error } = await supabase.rpc('withdraw_money', {
          user_id: user?.id,
          amount: numAmount
        });

        if (error) throw error;
        toast.success(`Successfully withdrew $${numAmount.toLocaleString()}`);
      }

      setAmount('');
      fetchUserData();
    } catch (error) {
      toast.error(`Error ${type === 'deposit' ? 'depositing' : 'withdrawing'} money`);
      console.error('Error:', error);
    } finally {
      setLoading(false);
    }
  }

  if (loading && !userData) {
    return (
      <div className="flex justify-center items-center h-[calc(100vh-4rem)]">
        <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
      </div>
    );
  }

  if (!user) {
    return (
      <div className="text-center mt-8">
        <h2 className="text-2xl font-bold mb-4">Please log in to access the bank</h2>
      </div>
    );
  }

  return (
    <div className="max-w-4xl mx-auto">
      <h1 className="text-3xl font-bold mb-6">City Bank</h1>
      
      {!userData?.has_bank_account ? (
        <div className="bg-gray-800 rounded-lg p-6 mb-8">
          <h2 className="text-2xl font-bold mb-4">Open a Bank Account</h2>
          <p className="mb-4">
            Would you like to open a bank account for $5,000? A bank account allows you to safely store your money and earn interest.
          </p>
          <button
            onClick={openAccount}
            disabled={loading || (userData?.money || 0) < 5000}
            className={`px-4 py-2 rounded-md ${
              (userData?.money || 0) < 5000
                ? 'bg-gray-600 cursor-not-allowed'
                : 'bg-green-600 hover:bg-green-700'
            }`}
          >
            {loading ? 'Processing...' : 'Open Account ($5,000)'}
          </button>
          {(userData?.money || 0) < 5000 && (
            <p className="mt-2 text-red-400">
              You need $5,000 to open a bank account. You currently have ${userData?.money.toLocaleString()}.
            </p>
          )}
        </div>
      ) : (
        <>
          <div className="bg-gray-800 rounded-lg p-6 mb-8">
            <div className="flex flex-col md:flex-row justify-between mb-6">
              <div>
                <h2 className="text-2xl font-bold mb-2">Your Account</h2>
                <p className="text-gray-300">
                  Cash: ${userData?.money.toLocaleString()}
                </p>
                <p className="text-gray-300">
                  Bank Balance: ${userData?.bank_money.toLocaleString()}
                </p>
                <p className="text-gray-300 mt-2">
                  You will earn 2% interest on your bank balance at each daily rollover.
                </p>
              </div>
              
              <div className="mt-4 md:mt-0">
                <div className="flex flex-col space-y-4">
                  <div>
                    <label htmlFor="amount" className="block text-sm font-medium text-gray-300 mb-1">
                      Amount
                    </label>
                    <input
                      type="number"
                      id="amount"
                      value={amount}
                      onChange={(e) => setAmount(e.target.value)}
                      className="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                      placeholder="Enter amount"
                      min="1"
                    />
                  </div>
                  
                  <div className="flex space-x-2">
                    <button
                      onClick={() => handleTransaction('deposit')}
                      disabled={loading}
                      className="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 rounded-md"
                    >
                      Deposit
                    </button>
                    <button
                      onClick={() => handleTransaction('withdraw')}
                      disabled={loading}
                      className="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 rounded-md"
                    >
                      Withdraw
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {transactions.length > 0 && (
            <div className="bg-gray-800 rounded-lg p-6">
              <h2 className="text-2xl font-bold mb-4">Recent Transactions</h2>
              <div className="overflow-x-auto">
                <table className="w-full">
                  <thead>
                    <tr className="text-left border-b border-gray-700">
                      <th className="pb-2">Date</th>
                      <th className="pb-2">Type</th>
                      <th className="pb-2 text-right">Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    {transactions.map((transaction) => (
                      <tr key={transaction.id} className="border-b border-gray-700">
                        <td className="py-3">
                          {new Date(transaction.created_at).toLocaleDateString()}
                        </td>
                        <td className="py-3 capitalize">
                          {transaction.type}
                        </td>
                        <td className={`py-3 text-right ${
                          transaction.type === 'deposit' ? 'text-red-400' : 'text-green-400'
                        }`}>
                          {transaction.type === 'deposit' ? '-' : '+'}${transaction.amount.toLocaleString()}
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          )}
        </>
      )}
    </div>
  );
}