import { Auth as SupabaseAuth } from '@supabase/auth-ui-react';
import { ThemeSupa } from '@supabase/auth-ui-shared';
import { supabase } from '../lib/supabase';
import { useNavigate } from 'react-router-dom';
import { useEffect } from 'react';
import { useSupabaseAuth } from '../hooks/useSupabaseAuth';

export function Auth() {
  const navigate = useNavigate();
  const { user } = useSupabaseAuth();

  useEffect(() => {
    if (user) {
      navigate('/');
    }
  }, [user, navigate]);

  return (
    <div className="max-w-md mx-auto mt-10 p-6 bg-gray-800 rounded-lg shadow-xl">
      <h2 className="text-2xl font-bold mb-6 text-center">Welcome to Mobster Story</h2>
      <SupabaseAuth 
        supabaseClient={supabase}
        appearance={{
          theme: ThemeSupa,
          variables: {
            default: {
              colors: {
                brand: '#22c55e',
                brandAccent: '#16a34a',
              },
            },
          },
        }}
        providers={[]}
      />
    </div>
  );
}